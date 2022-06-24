<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use App\DeliveryOrder;
use App\Order;
use App\Branch;
use App\Cso;
use App\CategoryProduct;
use App\Exports\OrderReportBranchExport;
use App\Exports\OrderReportCsoExport;
use App\Exports\OrderReportExport;
use App\User;
use App\RajaOngkir_City;
use App\HistoryUpdate;
use App\Promo;
use App\Product;
use App\Utils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class OrderController extends Controller
{
    //Buat menampilkan form Order
    public function index()
    {
    	$promos = DeliveryOrder::$Promo;
    	$branches = Branch::where('active', true)->orderBy("code", 'asc')->get();
    	$csos = Cso::where('active', true)->orderBy("code", 'asc')->get();
    	$cashUpgrades = Order::$CashUpgrade;
    	$paymentTypes = Order::$PaymentType;
        $banks = Order::$Banks;
        $categoryProducts = CategoryProduct::all();
        return view('order', compact('promos', 'branches', 'csos', 'cashUpgrades', 'paymentTypes', 'banks', 'categoryProducts'));
    }

    public function store(Request $request)
    {
  	   	$data = $request->all();
    	$data['code'] = "DO/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);
    	$data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
    	$data['30_cso_id'] = Cso::where('code', $data['30_cso_id'])->first()['id'];
    	$data['70_cso_id'] = Cso::where('code', $data['70_cso_id'])->first()['id'];

    	//pembentukan array product
    	$index = 0;
		$data['arr_product'] = [];
    	foreach ($data as $key => $value) {
    		$arrKey = explode("_", $key);
    		if ($arrKey[0] == 'product'){
    			if (isset($data['qty_'.$arrKey[1]])) {
    				$data['arr_product'][$index] = [];
    				$data['arr_product'][$index]['id'] = $value;

                    // {{-- KHUSUS Philiphin --}}
                    if ($value == 'other') {
                        $data['arr_product'][$index]['id'] = $data['product_other_'.$arrKey[1]];
                    }
                    //===========================

    				$data['arr_product'][$index]['qty'] = $data['qty_'.$arrKey[1]];
    				$index++;
    			}
    		}
    	}
    	$data['product'] = json_encode($data['arr_product']);

    	//pembentukan array Bank
    	$index = 0;
		$data['arr_bank'] = [];
    	foreach ($data as $key => $value) {
    		$arrKey = explode("_", $key);
    		if($arrKey[0] == 'bank'){
    			if(isset($data['cicilan_'.$arrKey[1]])){
    				$data['arr_bank'][$index] = [];
    				$data['arr_bank'][$index]['id'] = $value;
    				$data['arr_bank'][$index]['cicilan'] = $data['cicilan_'.$arrKey[1]];
    				$index++;
    			}
    		}
    	}
    	$data['bank'] = json_encode($data['arr_bank']);
    	$order = Order::create($data);

        if(isset($data['name-2'])){
            $order2 = $order->replicate();
            if(isset($data['name-2'])){
                $order->code .= "/1";
                $order->save();
                $order2->code .= "/2";
            }
            $order2->no_member = $data['no_member-2'];
            $order2->name = $data['name-2'];
            $order2->address = $data['address-2'];
            $order2->phone = $data['phone-2'];
            $order2->city = $data['city-2'];
            $order2->save();
        }
        $code = $order['code'];
        $url = "https://waki-indonesia.co.id/order-success?code=".$code."";
        $phone = preg_replace('/[^A-Za-z0-9\-]/', '', $order['phone']);
        if($phone[0]==0 || $phone[0]=="0"){
           $phone =  substr($phone, 1);
        }
        $phone = "62".$phone;
        // Utils::sendSms($phone, "Terima kasih telah melakukan transaksi di WAKi Indonesia. Berikut link detail transaksi anda (".$url."). Info lebih lanjut, hubungi 082138864962.");
    	return redirect()->route('order_success', ['code'=>$order['code']]);
    }

    public function successorder(Request $request)
    {
    	$order = Order::where('code', $request['code'])->first();
        $order['district'] = array($order->getDistrict());
        return view('order_success', compact('order'));
    }

    public function admin_AddOrder()
    {
        $promos = Promo::all();
        $branches = Branch::where('active', true)->orderBy("code", 'asc')->get();
        $csos = Cso::all();
        $cashUpgrades = Order::$CashUpgrade;
        $paymentTypes = Order::$PaymentType;
        $banks = Order::$Banks;
        $from_know = Order::$Know_From;
        return view('admin.add_order', compact('promos', 'branches', 'csos', 'from_know','cashUpgrades', 'paymentTypes', 'banks'));
    }

    public function admin_StoreOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            // dd($data);
            $data['code'] = "DO/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
            $data['30_cso_id'] = Cso::where('code', $data['30_cso_id'])->first()['id'];
            $data['70_cso_id'] = Cso::where('code', $data['70_cso_id'])->first()['id'];

            //pembentukan array product
            $index = 0;
            $data['arr_product'] = [];
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if ($arrKey[0] == 'product') {
                    if (isset($data['qty_' . $arrKey[1]])) {
                        $data['arr_product'][$index] = [];
                        $data['arr_product'][$index]['id'] = $value;

                        // {{-- KHUSUS Philiphin --}}
                        if ($value == 'other') {
                            $data['arr_product'][$index]['id'] = $data['product_other_' . $arrKey[1]];
                        }
                        //===========================

                        $data['arr_product'][$index]['qty'] = $data['qty_' . $arrKey[1]];
                        $index++;
                    }
                }
            }

            $data['product'] = json_encode($data['arr_product']);

            //pembentukan array Bank
            $index = 0;
            $data['arr_bank'] = [];
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if($arrKey[0] == 'bank'){
                    if(isset($data['cicilan_'.$arrKey[1]])){
                        $data['arr_bank'][$index] = [];
                        $data['arr_bank'][$index]['id'] = $value;
                        $data['arr_bank'][$index]['cicilan'] = $data['cicilan_'.$arrKey[1]];
                        $index++;
                    }
                }
            }
            $data['bank'] = json_encode($data['arr_bank']);
            $data['province'] = $data['province_id'];


            $data['image'] = [];
            $idxImg = 1;
            for ($i = 0; $i < 3; $i++) {
                if ($request->hasFile('images' . $i)) {
                    $path = "sources/order";
                    $file = $request->file('images' . $i);
                    $fileName = str_replace([' ', ':'], '', Carbon::now()->toDateTimeString()). $idxImg . "_order." . $file->getClientOriginalExtension();

                    // Cek ada folder tidak
                    if (!is_dir($path)) {
                        File::makeDirectory($path, 0777, true, true);
                    }

                    //compressed img
                    $compres = Image::make($file->getRealPath());
                    $compres->resize(540, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path.'/'.$fileName);

                    //array_push($data['image'], $fileName);
                    $data['image'][$i] = $fileName;
                    $idxImg++;
                }
            }
            $data['status'] = "new";

            $order = Order::create($data);
            DB::commit();

            $code = $order['code'];
            $url = "https://waki-indonesia.co.id/order-success?code=".$code."";
            $phone = preg_replace('/[^A-Za-z0-9\-]/', '', $order['phone']);
            if($phone[0]==0 || $phone[0]=="0"){
               $phone =  substr($phone, 1);
            }
            $phone = "62".$phone;
            // Utils::sendSms($phone, "Terima kasih telah melakukan transaksi di WAKi Indonesia. Berikut link detail transaksi anda (".$url."). Info lebih lanjut, hubungi 082138864962.");
            //return redirect()->route('detail_order', ['code'=>$order['code']]);
            return response()->json(['success' => $order]);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['errors' => $ex->getMessage()]);
        }
    }

    public function admin_ListOrder(Request $request)
    {
        $branches = Branch::Where('active', true)->orderBy("code", 'asc')->get();
        // Khusus head-manager, head-admin, admin
        $orders = Order::where('active', true);

        // Khusus akun CSO
        if (Auth::user()->roles[0]['slug'] == 'cso') {
            $orders = Order::where('cso_id', Auth::user()->cso['id'])->where('active', true);
        }

        // Khusus akun branch dan area-manager
        if (
            Auth::user()->roles[0]['slug'] == 'branch'
            || Auth::user()->roles[0]['slug'] == 'area-manager'
        ) {
            $arrbranches = [];
            foreach (Auth::user()->listBranches() as $value) {
                array_push($arrbranches, $value['id']);
            }
            $orders = Order::WhereIn('branch_id', $arrbranches)->where('active', true);
        }

        // Kalau ada Filter
        if (
            $request->has('filter_branch')
            && Auth::user()->roles[0]['slug'] != 'branch'
        ) {
            $orders = $orders->where('branch_id', $request->filter_branch);
        }

        if ($request->has('filter_province')) {
            $orders = $orders->where('province', $request->filter_province);
        }

        if ($request->has('filter_city')) {
            $orders = $orders->where('city', $request->filter_city);
        }

        if ($request->has('filter_district')) {
            $orders = $orders->where('distric', $request->filter_district);
        }

        if ($request->has('filter_cso') && Auth::user()->roles[0]['slug'] != 'cso') {
            $orders = $orders->where('cso_id', $request->filter_cso);
        }

        if ($request->has('filter_type') && Auth::user()->roles[0]['slug'] != 'cso') {
            $orders = $orders->where('customer_type', $request->filter_type);
        }

        if ($request->has('filter_promo')) {
            $orders = $orders->where('product', 'like', '%"id":"'.$request->filter_promo.'"%');
        }

        if ($request->has('filter_status')) {
            $orders = $orders->where('status', $request->filter_status);
        }

        if ($request->has("filter_string")) {
            $filterString = $request->filter_string;
            $orders = $orders->where(
                function ($query) use ($filterString) {
                    $query->where(
                        "name",
                        "like",
                        "%" . $filterString . "%"
                    )
                    ->orWhere(
                        "phone",
                        "like",
                        "%" . $filterString . "%"
                    )
                    ->orWhere(
                        "code",
                        "like",
                        "%" . $filterString . "%"
                    );
                }
            );
        }

        $countOrders = $orders->count();
        $categories = CategoryProduct::all();
        $promos = Promo::all();
        $orders = $orders->sortable(['orderDate' => 'desc'])->paginate(10);

        return view('admin.list_order', compact('orders','countOrders','branches', 'categories', 'promos'));
    }

    public function admin_DetailOrder(Request $request)
    {
        $branch = Branch::where('code', 'F00')->first();
        $csos = Cso::where('branch_id', $branch->id)->orderBy('code')->get();
        $order = Order::where('code', $request['code'])->first();
        $csoDeliveryOrders = Cso::whereIn('id', json_decode($order['delivery_cso_id']) ?? [])->get();
        $order['district'] = $order->getDistrict();
        $historyUpdateOrder = HistoryUpdate::leftjoin('users','users.id', '=','history_updates.user_id' )
        ->select('history_updates.method', 'history_updates.created_at','history_updates.meta as meta' ,'users.name as name')
        ->where('type_menu', 'Order')->where('menu_id', $order->id)->get();
        return view('admin.detail_order', compact('order', 'historyUpdateOrder', 'csos', 'csoDeliveryOrders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if($request->has('id')){
            $orders = Order::find($request->get('id'));
            $orders['district'] = $orders->getDistrict();
            $promos = Promo::all();
            $branches = Branch::all()->sortBy("code");
            $csos = Cso::all();
            $cashUpgrades = Order::$CashUpgrade;
            $paymentTypes = Order::$PaymentType;
            $banks = Order::$Banks;
            $from_know = Order::$Know_From;

            $arr_price = $orders->getPrice();
            return view('admin.update_order', compact('orders','promos', 'from_know','branches', 'csos', 'cashUpgrades', 'paymentTypes', 'banks', 'arr_price'));
        }else{
            return response()->json(['result' => 'Gagal!!']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // return response()->json(['errors' => $request->all()], 500);
        DB::beginTransaction();
        try{
            $historyUpdate= [];
            $data = $request->all();
            $orders = Order::find($request->input('idOrder'));
            $dataBefore = Order::find($request->input('idOrder'));
            $orders['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
            $orders['30_cso_id'] = Cso::where('code', $data['30_cso_id'])->first()['id'];
            $orders['70_cso_id'] = Cso::where('code', $data['70_cso_id'])->first()['id'];

            $orders['no_member'] = $data['no_member'];
            $orders['name'] = $data['name'];
            $orders['address'] = $data['address'];
            $orders['cash_upgrade'] = $data['cash_upgrade'];
            $orders['prize'] = $data['prize'];
            $orders['payment_type'] = $data['payment_type'];
            $orders['total_payment'] = $data['total_payment'];
            $orders['down_payment'] = $data['down_payment'];
            $orders['remaining_payment'] = $data['remaining_payment'];
            $orders['customer_type'] = $data['customer_type'];
            $orders['description'] = $data['description'];
            $orders['phone'] = $data['phone'];
            $orders['know_from'] = $data['know_from'];
            $orders['province'] = $data['province_id'];
            $orders['city'] = $data['city'];
            $orders['distric'] = $data['distric'];

            //pembentukan array product
            $index = 0;
            $data['arr_product'] = [];
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if($arrKey[0] == 'product'){
                    if(isset($data['qty_'.$arrKey[1]])){
                        $data['arr_product'][$index] = [];
                        $data['arr_product'][$index]['id'] = $value;

                        // {{-- KHUSUS Philiphin --}}
                        if($value == 'other'){
                            $data['arr_product'][$index]['id'] = $data['product_other_'.$arrKey[1]];
                        }
                        //===========================

                        $data['arr_product'][$index]['qty'] = $data['qty_'.$arrKey[1]];
                        $index++;
                    }
                }
            }
            $orders['product'] = json_encode($data['arr_product']);

            //pembentukan array Bank
            $index = 0;
            $data['arr_bank'] = [];
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if($arrKey[0] == 'bank'){
                    if(isset($data['cicilan_'.$arrKey[1]])){
                        $data['arr_bank'][$index] = [];
                        $data['arr_bank'][$index]['id'] = $value;
                        $data['arr_bank'][$index]['cicilan'] = $data['cicilan_'.$arrKey[1]];
                        $index++;
                    }
                }
            }
            $orders['bank'] = json_encode($data['arr_bank']);

            //update image
            $arr_image_before = $orders['image'];
            $namaGambar = [];
            if($arr_image_before != null){
                $namaGambar = array_values($arr_image_before);
            }
            $idxImg = 1;

            if ($request->hasFile('images0') || $request->hasFile('images1') || $request->hasFile('images2')){
                // Store image
                for ($i = 0; $i < $request->total_images; $i++) {
                    if ($request->hasFile('images' . $i)) {
                        if($arr_image_before != null){
                            if (array_key_exists($i, $arr_image_before)) {
                                if (File::exists("sources/order/" . $arr_image_before[$i])) {
                                    File::delete("sources/order/" . $arr_image_before[$i]);
                                }
                            }
                        }

                        $path = "sources/order";
                        $file = $request->file('images' . $i);
                        $fileName = str_replace([' ', ':'], '', Carbon::now()->toDateTimeString()). $idxImg . "_order." . $file->getClientOriginalExtension();

                        //compressed img
                        $compres = Image::make($file->getRealPath());
                        $compres->resize(540, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($path.'/'.$fileName);

                        //array_push($data['image'], $fileName);
                        $namaGambar[$i] = $fileName;
                        $idxImg++;
                    } else {
                        if($arr_image_before != null){
                            if (array_key_exists($i, $arr_image_before)) {
                                $namaGambar[$i] = $arr_image_before[$i];
                            }
                        }
                    }
                }
            }

            if ($request->dlt_img != "") {
                $deletes = explode(",", $request->dlt_img);
                foreach ($deletes as $value) {
                    if (array_key_exists($value, $namaGambar)) {
                        if (File::exists("sources/order/" . $codePath . "/" . $namaGambar[$value])) {
                            File::delete("sources/order/" . $codePath . "/" . $namaGambar[$value]);
                        }
                        unset($namaGambar[$value]);
                    }
                }
            }

            // $namaGambarFix = "[";

            // for ($key = 0; $key < 3; $key++) {
            //     if ($key == 2) {
            //         if (array_key_exists($key, $namaGambar)) {
            //             $namaGambarFix .= '"' . $namaGambar[$key] . '"';
            //         } else {
            //             $namaGambarFix .= '""';
            //         }
            //     } else {
            //         if (array_key_exists($key, $namaGambar)) {
            //             $namaGambarFix .= '"' . $namaGambar[$key] . '",';
            //         } else {
            //             $namaGambarFix .= '"",';
            //         }
            //     }
            // }

            //$namaGambarFix .= "]";
            $orders['image'] = $namaGambar;
            $orders->save();

            $orders['image'] = json_encode($orders['image']);
            $dataBefore['image'] = json_encode($dataBefore['image']);

            $user = Auth::user();
            $historyUpdate['type_menu'] = "Order";
            $historyUpdate['method'] = "Update";
            $historyUpdate['meta'] = json_encode([
                'user'=>$user['id'],
                'createdAt' => date("Y-m-d h:i:s"),
                'dataChange'=> array_diff(json_decode($orders, true), json_decode($dataBefore,true))
            ]);

            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $orders->id;
            $createData = HistoryUpdate::create($historyUpdate);
            DB::commit();
            return response()->json(['success' => 'Berhasil!']);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['errors' => $ex->getMessage()], 500);
        }

    }

    public function updateStatusOrder(Request $request)
    {
        $order = Order::find($request->input('orderId'));
        $last_status_order = $order->status;
        $order->status = $request->input('status_order');
        
        // Save Delivery CSO
        if ($order->status == Order::$status['3']) {
            $order->delivery_cso_id = json_encode($request->delivery_cso_id);
        }        
        $order->save();
        
        return redirect()->back()->with('success', 'Status Order Berhasil Di Ubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try{
            $order = Order::where('id', $id)->first();
            $order->active = false;
            $order->save();
            // return view('admin.list_order');

            $user = Auth::user();
            $historyUpdate= [];
            $historyUpdate['type_menu'] = "Order";
            $historyUpdate['method'] = "Delete";
            $historyUpdate['meta'] = json_encode(['user'=>$user['id'],'createdAt' => date("Y-m-d h:i:s"), 'dateChange'=> json_encode(array('Active'=>$order->active))]);
            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $id;
            $createData = HistoryUpdate::create($historyUpdate);
            DB::commit();
            return redirect()->route('admin_list_order')->with('success', 'Data Berhasil Di Hapus'); //response()->json(['success' => 'Berhasil']);
        }catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' =>  $ex->getMessage(), 500]);
        }
    }

    public function export_to_xls(Request $request)
    {
        $start_date = null;
        $end_date = null;
        $cso = null;
        $city = null;
        $category = null;
        $promo = null;
        if($request->has('start_orderDate') && $request->start_orderDate != "undefined"){
            $start_date = $request->start_orderDate;
        }
        if($request->has('end_orderDate') && $request->end_orderDate != "undefined"){
            $end_date = $request->end_orderDate;
        }
        if($request->has('cso') && $request->cso != "undefined"){
            $cso = $request->cso;
        }
        if($request->has('city') && $request->city != "undefined"){
            $city = $request->city;
        }
        if($request->has('category') && $request->category != "undefined"){
            $category = $request->category;
        }
        if($request->has('filter_promo') && $request->filter_promo != "undefined"){
            $promo = $request->filter_promo;
        }

        return Excel::download(new OrderExport($start_date, $end_date, $city, $category, $cso, $promo), 'Order Report.xlsx');
    }

    public function ListOrderforSubmission(Request $request)
    {
        if($request->has('submission_id')){
            $branch_id = \App\Submission::find($request->submission_id)->branch['id'];
        }
        else{
            $branch_id = $request->branch_id;
        }

        DB::beginTransaction();
        try {
            $orders = Order::Where([['active', true], ['branch_id', $branch_id]]);
            if($request->filter != ""){
                $filter = $request->filter;
                $orders = $orders->where(function ($q) use ($filter) {
                                return $q->where("name", "like", "%" . $filter . "%")->orWhere("phone", "like", "%" . $filter . "%");
                            });
            }
            $orders = $orders->orderBy('id', 'DESC')->take(20)->get();

            $promos = Promo::all();
            $productDb = [];

            forEach($promos as $productNya){
                $namaNya = "";
                forEach($productNya->productName() as $idxNya => $perName){
                    $namaNya .= $perName;
                    if($idxNya < sizeof($productNya->productName())-1){
                        $namaNya .= " & ";
                    }
                }
                $productDb[$productNya->id] = $namaNya;
            }

            $data = ['orders' => $orders, 'productDb' => $productDb];
            return response()->json($data, 200);
        }
        catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // Order Report
    public function admin_ListOrderReport(Request $request)
    {
        $startDate = date('Y-m-01');
        if ($request->has('filter_start_date')) {
            $startDate = date('Y-m-d', strtotime($request->filter_start_date));
        }
        $endDate = date('Y-m-d');
        if ($request->has('filter_end_date')) {
            $endDate = date('Y-m-d', strtotime($request->filter_end_date));
        }
        $yesterdayDate = date('Y-m-d', strtotime("-1 days", strtotime($endDate)));

        $query_total_sale_untilYesterday = "SELECT SUM(o.total_payment) 
            FROM orders as o
            WHERE o.branch_id = b.id
            AND o.orderDate >= '$startDate'
            AND o.orderDate <= '$yesterdayDate'
            AND (o.status = '" . Order::$status['2'] . "'
            OR o.status = '" . Order::$status['3'] . "' 
            OR o.status = '" . Order::$status['4'] . "')";
        $query_total_sale_today = "SELECT SUM(o.total_payment) 
            FROM orders as o
            WHERE o.branch_id = b.id
            AND o.orderDate = '$endDate'
            AND (o.status = '" . Order::$status['2'] . "' 
            OR o.status = '" . Order::$status['3'] . "'
            OR o.status = '" . Order::$status['4'] . "')";

        $order_reports = Branch::from('branches as b')
            ->select('b.*')
            ->selectRaw("($query_total_sale_untilYesterday) as total_sale_untilYesterday")
            ->selectRaw("($query_total_sale_today) as total_sale_today")
            ->where('active', true)->orderBy('code')->get();
        $countOrderReports = $order_reports->count();

        return view('admin.list_orderreport', compact('startDate', 'endDate', 'order_reports', 'countOrderReports'));
    }

    public function admin_ListOrderReportBranch(Request $request)
    {
        $branches = Branch::where('active', true)->orderBy('code', 'asc')->get();

        $startDate = date('Y-m-01');
        if ($request->has('filter_start_date')) {
            $startDate = date('Y-m-d', strtotime($request->filter_start_date));
        }
        $endDate = date('Y-m-d');
        if ($request->has('filter_end_date')) {
            $endDate = date('Y-m-d', strtotime($request->filter_end_date));
        }
        $yesterdayDate = date('Y-m-d', strtotime("-1 days", strtotime($endDate)));

        $query_total_sale_untilYesterday = "SELECT SUM(o.total_payment) 
            FROM orders as o
            WHERE o.cso_id = c.id
            AND o.orderDate >= '$startDate'
            AND o.orderDate <= '$yesterdayDate'
            AND (o.status = '" . Order::$status['2'] . "'
            OR o.status = '" . Order::$status['3'] . "' 
            OR o.status = '" . Order::$status['4'] . "')";
        $query_total_sale_today = "SELECT SUM(o.total_payment) 
            FROM orders as o
            WHERE o.cso_id = c.id
            AND o.orderDate = '$endDate'
            AND (o.status = '" . Order::$status['2'] . "' 
            OR o.status = '" . Order::$status['3'] . "'
            OR o.status = '" . Order::$status['4'] . "')";

        $currentBranch = null;
        if ($request->has('filter_branch')) {
            $currentBranch = Branch::find($request->filter_branch);
            $query_total_sale_untilYesterday .= " AND o.branch_id = " . $currentBranch['id'];
            $query_total_sale_today .= " AND o.branch_id = " . $currentBranch['id'];
        } 

        $order_reports = Cso::from('csos as c')
            ->select('c.*')
            ->selectRaw("($query_total_sale_untilYesterday) as total_sale_untilYesterday")
            ->selectRaw("($query_total_sale_today) as total_sale_today")
            ->where('active', true)->orderBy('code')
            ->having("total_sale_untilYesterday", ">", 0)
            ->orHaving("total_sale_today", ">", 0)
            ->get();
        $countOrderReports = $order_reports->count();

        return view('admin.list_orderreport_branch', compact('startDate', 'endDate', 'branches', 'currentBranch', 'order_reports', 'countOrderReports'));
    }

    public function admin_ListOrderReportCso(Request $request)
    {
        $branches = Branch::where('active', true)->orderBy('code', 'asc')->get();
        $csos = Cso::where("active", true)->orderBy("code", 'asc')->get();

        $startDate = date('Y-m-01');
        if ($request->has('filter_start_date')) {
            $startDate = date('Y-m-d', strtotime($request->filter_start_date));
        }
        $endDate = date('Y-m-d');
        if ($request->has('filter_end_date')) {
            $endDate = date('Y-m-d', strtotime($request->filter_end_date));
        }
        
        $order_reports = Order::where('orderDate', '>=', $startDate)
            ->where('orderDate', '<=', $endDate);

        $currentBranch = null;
        if ($request->has('filter_branch')) {
            $currentBranch = Branch::find($request->filter_branch);
            $order_reports->where('branch_id', $currentBranch['id']);
        }
        $currentCso = null;
        if ($request->has('filter_cso')) {
            $currentCso = Cso::where("code", $request->filter_cso)->first();
            $order_reports->where('cso_id', $currentCso['id']);
        }

        $order_reports = $order_reports->where(function($query) {
                $query->where('status', Order::$status['2'])
                    ->orWhere('status', Order::$status['3'])
                    ->orWhere('status', Order::$status['4']);
            })
            ->orderBy('orderDate', 'desc')->get();
        $countOrderReports = $order_reports->count();

        return view('admin.list_orderreport_cso', compact('startDate', 'endDate', 'branches', 'csos', 'currentBranch', 'currentCso', 'order_reports', 'countOrderReports'));
    }

    // Export or Print Order Report
    public function admin_ExportOrderReport(Request $request)
    {
        $startDate = date('Y-m-01');
        if ($request->has('filter_start_date')) {
            $startDate = date('Y-m-d', strtotime($request->filter_start_date));
        }
        $endDate = date('Y-m-d');
        if ($request->has('filter_end_date')) {
            $endDate = date('Y-m-d', strtotime($request->filter_end_date));
        }
        $yesterdayDate = date('Y-m-d', strtotime("-1 days", strtotime($endDate)));

        $query_total_sale_untilYesterday = "SELECT SUM(o.total_payment) 
            FROM orders as o
            WHERE o.branch_id = b.id
            AND o.orderDate >= '$startDate'
            AND o.orderDate <= '$yesterdayDate'
            AND (o.status = '" . Order::$status['2'] . "' 
            OR o.status = '" . Order::$status['3'] . "'
            OR o.status = '" . Order::$status['4'] . "')";
        $query_total_sale_today = "SELECT SUM(o.total_payment) 
            FROM orders as o
            WHERE o.branch_id = b.id
            AND o.orderDate = '$endDate'
            AND (o.status = '" . Order::$status['2'] . "' 
            OR o.status = '" . Order::$status['3'] . "'
            OR o.status = '" . Order::$status['4'] . "')";

        $order_reports = Branch::from('branches as b')
            ->select('b.*')
            ->selectRaw("($query_total_sale_untilYesterday) as total_sale_untilYesterday")
            ->selectRaw("($query_total_sale_today) as total_sale_today")
            ->where('active', true)->orderBy('code')->get();
        $countOrderReports = $order_reports->count();

        if ($request->export_type == "print") {
            return view('admin.list_orderreportPdf', compact('startDate', 'endDate', 'order_reports', 'countOrderReports'));
        } else if ($request->export_type == "xls") {
            return Excel::download(new OrderReportExport($startDate, $endDate, $order_reports, $countOrderReports), 'Order Report.xlsx');
        }
    }

    public function admin_ExportOrderReportBranch(Request $request)
    {
        $startDate = date('Y-m-01');
        if ($request->has('filter_start_date')) {
            $startDate = date('Y-m-d', strtotime($request->filter_start_date));
        }
        $endDate = date('Y-m-d');
        if ($request->has('filter_end_date')) {
            $endDate = date('Y-m-d', strtotime($request->filter_end_date));
        }
        $yesterdayDate = date('Y-m-d', strtotime("-1 days", strtotime($endDate)));

        $query_total_sale_untilYesterday = "SELECT SUM(o.total_payment) 
            FROM orders as o
            WHERE o.cso_id = c.id
            AND o.orderDate >= '$startDate'
            AND o.orderDate <= '$yesterdayDate'
            AND (o.status = '" . Order::$status['2'] . "' 
            OR o.status = '" . Order::$status['3'] . "'
            OR o.status = '" . Order::$status['4'] . "')";
        $query_total_sale_today = "SELECT SUM(o.total_payment) 
            FROM orders as o
            WHERE o.cso_id = c.id
            AND o.orderDate = '$endDate'
            AND (o.status = '" . Order::$status['2'] . "' 
            OR o.status = '" . Order::$status['3'] . "'
            OR o.status = '" . Order::$status['4'] . "')";

        $currentBranch = null;
        if ($request->has('filter_branch')) {
            $currentBranch = Branch::find($request->filter_branch);
            $query_total_sale_untilYesterday .= " AND o.branch_id = " . $currentBranch['id'];
            $query_total_sale_today .= " AND o.branch_id = " . $currentBranch['id'];
        } 

        $order_reports = Cso::from('csos as c')
            ->select('c.*')
            ->selectRaw("($query_total_sale_untilYesterday) as total_sale_untilYesterday")
            ->selectRaw("($query_total_sale_today) as total_sale_today")
            ->where('active', true)->orderBy('code')
            ->having("total_sale_untilYesterday", ">", 0)
            ->orHaving("total_sale_today", ">", 0)
            ->get();
        $countOrderReports = $order_reports->count();

        if ($request->export_type == "print") {
            return view('admin.list_orderreport_branchPdf', compact('startDate', 'endDate', 'currentBranch', 'order_reports', 'countOrderReports'));
        } else if ($request->export_type == "xls") {
            return Excel::download(new OrderReportBranchExport($startDate, $endDate, $order_reports, $countOrderReports, $currentBranch), 'Order Report Branch.xlsx');
        }
    }

    public function admin_ExportOrderReportCso(Request $request)
    {
        $startDate = date('Y-m-01');
        if ($request->has('filter_start_date')) {
            $startDate = date('Y-m-d', strtotime($request->filter_start_date));
        }
        $endDate = date('Y-m-d');
        if ($request->has('filter_end_date')) {
            $endDate = date('Y-m-d', strtotime($request->filter_end_date));
        }
        
        $order_reports = Order::where('orderDate', '>=', $startDate)
            ->where('orderDate', '<=', $endDate);

        $currentBranch = null;
        if ($request->has('filter_branch')) {
            $currentBranch = Branch::find($request->filter_branch);
            $order_reports->where('branch_id', $currentBranch['id']);
        }
        $currentCso = null;
        if ($request->has('filter_cso')) {
            $currentCso = Cso::where("code", $request->filter_cso)->first();
            $order_reports->where('cso_id', $currentCso['id']);
        }

        $order_reports = $order_reports->where(function($query) {
                $query->where('status', Order::$status['2'])
                    ->orWhere('status', Order::$status['3'])
                    ->orWhere('status', Order::$status['4']);
            })
            ->orderBy('orderDate', 'desc')->get();
        $countOrderReports = $order_reports->count();

        if ($request->export_type == "print") {
            return view('admin.list_orderreport_csoPdf', compact('startDate', 'endDate', 'currentBranch', 'currentCso', 'order_reports', 'countOrderReports'));
        } else if ($request->export_type == "xls") {
            return Excel::download(new OrderReportCsoExport($startDate, $endDate, $order_reports, $countOrderReports, $currentBranch, $currentCso), 'Order Report Cso.xlsx');
        }
    }

    public function fetchDetailPromo($promo_id)
    {
        $promos = Promo::find($promo_id);
        return json_encode($promos);
    }

    //KHUSUS API APPS
    public function fetchBanksApi()
    {
        $data = Order::$Banks;
        $banks = [];
        foreach ($data as $key => $value) {
            $temp = [];
            $temp['id'] = $key;
            $temp['name'] = $value;
            array_push($banks, $temp);
        }
        $data = ['result' => 1,
                     'data' => $banks
                    ];
        return response()->json($data, 200);
    }

    public function fetchKnowFromApi()
    {
        $data = Order::$Know_From;
        $know_From = [];
        foreach($data as $key => $value) {
            $tmp = [];
            $tmp['id'] = $key;
            $tmp['know_from'] = $value;
            array_push($know_From, $tmp);
        }

        $data = ['result' => count($know_From),
                 'data' => $know_From
                ];

        return response()->json($data, 200);
    }

    public function addApi(Request $request)
    {
        $messages = array(
                'cso_id.required' => 'The CSO Code field is required.',
                'cso_id.exists' => 'Wrong CSO Code.',
                'branch_id.required' => 'The Branch must be selected.',
                'old_product.required_if' => 'The old product field is required when upgrade.'
            );

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'distric' => 'required',
            'city' => 'required',
            'province' => 'required',
            'cash_upgrade' => 'required',
            'product_0' => 'required',
            'know_from' => 'required',
            'qty_0' => 'required',
            'old_product' => 'required_if:cash_upgrade,==,2',
            'payment_type' => 'required',
            'bank_0' => 'required',
            'cicilan_0' => 'required',
            'total_payment' => 'required',
            'down_payment' => 'required',
            'remaining_payment' => 'required',
            'cso_id' => ['required', 'exists:csos,code'],
            'cso_id_30' => ['required', 'exists:csos,code'],
            'cso_id_70' => ['required', 'exists:csos,code'],
            'branch_id' => 'required'
        ], $messages);

        if ($validator->fails()){
            $data = ['result' => 0,
                     'data' => $validator->errors()
                    ];
            return response()->json($data, 401);
        }
        else{
            $data = $request->all();
            $data['code'] = "DO/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
            $data['30_cso_id'] = Cso::where('code', $data['cso_id_30'])->first()['id'];
            $data['70_cso_id'] = Cso::where('code', $data['cso_id_70'])->first()['id'];
            $data['prize'] = $data['gift_product'];
            //pembentukan array product
            $index = 0;
            $data['arr_product'] = [];
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if($arrKey[0] == 'product'){
                    if(isset($data['qty_'.$arrKey[1]])){
                        $data['arr_product'][$index] = [];
                        $data['arr_product'][$index]['id'] = $value;

                        // {{-- KHUSUS Philiphin --}}
                        if($value == 'other'){
                            $data['arr_product'][$index]['id'] = $data['product_other_'.$arrKey[1]];
                        }
                        //===========================

                        $data['arr_product'][$index]['qty'] = $data['qty_'.$arrKey[1]];
                        $index++;
                    }
                }
            }
            $data['product'] = json_encode($data['arr_product']);

            //pembentukan array Bank
            $index = 0;
            $data['arr_bank'] = [];
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if($arrKey[0] == 'bank'){
                    if(isset($data['cicilan_'.$arrKey[1]])){
                        $data['arr_bank'][$index] = [];
                        $data['arr_bank'][$index]['id'] = $value;
                        $data['arr_bank'][$index]['cicilan'] = $data['cicilan_'.$arrKey[1]];
                        $index++;
                    }
                }
            }
            $data['bank'] = json_encode($data['arr_bank']);
            $order = Order::create($data);

            if(isset($data['name_2'])){
                $order2 = $order->replicate();
                if(isset($data['name_2'])){
                    $order->code .= "/1";
                    $order->save();
                    $order2->code .= "/2";
                }
                $order2->no_member = $data['no_member_2'];
                $order2->name = $data['name_2'];
                $order2->address = $data['address_2'];
                $order2->phone = $data['phone_2'];
                $order2->city = $data['city_2'];
                $order2->save();
            }

            $order['URL'] = route('order_success')."?code=".$order['code'];

            $order['cso_id_30'] = $order['30_cso_id'];
            $order['cso_id_70'] = $order['70_cso_id'];
            $order['prize_product'] = $order['prize'];

            $data = ['result' => 1,
                     'data' => $order
                    ];
            return response()->json($data, 200);
        }
    }

    public function listApi(Request $request)
    {
        $messages = array(
                'user_id.required' => 'There\'s an error with the data.',
                'user_id.exists' => 'There\'s an error with the data.'
            );

        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'exists:users,id'],
        ], $messages);

        if ($validator->fails()){
            $data = ['result' => 0,
                     'data' => $validator->errors()
                    ];
            return response()->json($data, 401);
        }
        else{
            $data = $request->all();
            $userNya = User::find($data['user_id']);

            //khususu head-manager, head-admin, admin
            $orders = Order::where('orders.active', true);

            //khusus akun CSO
            if($userNya->roles[0]['slug'] == 'cso'){
                $csoIdUser = $userNya->cso['id'];
                $orders = Order::where([['orders.active', true], ['orders.cso_id', $csoIdUser]]);
            }

            //khusus akun branch dan area-manager
            if($userNya->roles[0]['slug'] == 'branch' || $userNya->roles[0]['slug'] == 'area-manager'){
                $arrbranches = [];
                foreach ($userNya->listBranches() as $value) {
                    array_push($arrbranches, $value['id']);
                }
                $orders = Order::WhereIn('orders.branch_id', $arrbranches)->where('orders.active', true);
            }
            $orders = $orders->leftjoin('branches', 'orders.branch_id', '=', 'branches.id')
                                ->leftjoin('csos', 'orders.cso_id', '=', 'csos.id')
                                ->select('orders.id', 'orders.code', 'orders.created_at', 'orders.name as customer_name', 'orders.product', 'orders.province','orders.city', 'orders.distric','branches.id as branch_id','branches.code as branch_code', 'branches.name as branch_name', 'csos.code as cso_code', 'csos.name as cso_name');
            // dd($orders);
            if($request->has('filter_branch')){
                $orders = $orders->where('orders.branch_id', $request->filter_branch);
            }
            if($request->has('filter_cso')){
                $orders = $orders->where('orders.cso_id', $request->filter_cso);
            }
            if($request->has('filter_startDate')&& $request->has('filter_endDate')){
                $orders = $orders->whereBetween('orders.orderDate', [date($request->filter_startDate), date($request->filter_endDate)]);
            }
            if($request->has('filter_province')){
                $orders = $orders->where('orders.province', $request->filter_province);
            }
            if($request->has('filter_city')){
                $orders = $orders->where('orders.city', $request->filter_city);
            }
            if($request->has('filter_district')){
                $orders = $orders->where('orders.distric', $request->filter_district);
            }
            $orders = $orders->orderBy('orderDate', 'DESC');
            $orders = $orders->paginate($request->limit);
            foreach ($orders as $i => $doNya) {
                $tempId = json_decode($doNya['product'], true);
                $tempArray = $doNya['product'];
                $tempArray = [];
                foreach ($tempId as $j => $product) {
                    $tempArray[$j] = [];
                    $tempArray[$j]['name'] = $product['id'];
                    if(isset(DeliveryOrder::$Promo[$product['id']])){
                        $tempArray[$j]['name'] = DeliveryOrder::$Promo[$product['id']]['name'];
                    }
                    $tempArray[$j]['qty'] = $product['qty'];
                }
                $doNya['product'] = $tempArray;
                $doNya['district'] = $doNya->getDistrict();
            }

            $data = ['result' => 1,
                     'data' => $orders
                    ];
            return response()->json($data, 200);
        }
    }

    public function updateApi(Request $request)
    {
        $messages = array(
            'id.required' => 'There\'s an error with the data.',
            'id.exists' => 'There\'s an error with the data.',
            'cso_id.required' => 'The CSO Code field is required.',
            'cso_id.exists' => 'Wrong CSO Code.',
            'branch_id.required' => 'The Branch must be selected.',
            'old_product.required_if' => 'The old product field is required when upgrade.'
        );

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'distric' => 'required',
            'city' => 'required',
            'province' => 'required',
            'cash_upgrade' => 'required',
            'product_0' => 'required',
            'know_from' => 'required',
            'qty_0' => 'required',
            'old_product' => 'required_if:cash_upgrade,==,2',
            'payment_type' => 'required',
            'bank_0' => 'required',
            'cicilan_0' => 'required',
            'total_payment' => 'required',
            'down_payment' => 'required',
            'remaining_payment' => 'required',
            'cso_id' => ['required', 'exists:csos,code'],
            'cso_id_30' => ['required', 'exists:csos,code'],
            'cso_id_70' => ['required', 'exists:csos,code'],
            'branch_id' => 'required'
        ], $messages);

        if ($validator->fails()) {
            $data = [
                'result' => 0,
                'data' => $validator->errors(),
            ];

            return response()->json($data, 401);
        } else {
            $data = $request->all();
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
            $data['30_cso_id'] = Cso::where('code', $data['cso_id_30'])->first()['id'];
            $data['70_cso_id'] = Cso::where('code', $data['cso_id_70'])->first()['id'];
            $data['prize'] = $data['gift_product'];

            // Pembentukan array product
            $index = 0;
            $data['arr_product'] = [];
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if($arrKey[0] == 'product'){
                    if(isset($data['qty_'.$arrKey[1]])){
                        $data['arr_product'][$index] = [];
                        $data['arr_product'][$index]['id'] = $value;

                        // {{-- KHUSUS Philiphin --}}
                        if($value == 'other'){
                            $data['arr_product'][$index]['id'] = $data['product_other_'.$arrKey[1]];
                        }
                        //===========================

                        $data['arr_product'][$index]['qty'] = $data['qty_'.$arrKey[1]];
                        $index++;
                    }
                }
            }
            $data['product'] = json_encode($data['arr_product']);

            // Pembentukan array Bank
            $index = 0;
            $data['arr_bank'] = [];
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if($arrKey[0] == 'bank'){
                    if(isset($data['cicilan_'.$arrKey[1]])){
                        $data['arr_bank'][$index] = [];
                        $data['arr_bank'][$index]['id'] = $value;
                        $data['arr_bank'][$index]['cicilan'] = $data['cicilan_'.$arrKey[1]];
                        $index++;
                    }
                }
            }
            $data['bank'] = json_encode($data['arr_bank']);
            $order = Order::find($data['id']);
            $order->fill($data)->save();

            // Menyimpan riwayat pembaruan ke tabel history_updates
            $historyOrder = [];
            $historyOrder["type_menu"] = "Order";
            $historyOrder["method"] = "Update";
            $historyOrder["meta"] = [
                "user" => $request->user_id,
                "createdAt" => date("Y-m-d h:i:s"),
                'dataChange'=> $order->getChanges(),
            ];
            $historyOrder["user_id"] = $request->user_id;
            $historyOrder["menu_id"] = $request->id;

            HistoryUpdate::create($historyOrder);

            $data = [
                'result' => 1,
                'data' => $order,
            ];

            return response()->json($data, 200);
        }
    }

    public function viewApi($id)
    {
        //khususu head-manager, head-admin, admin
        $orders = Order::where([['orders.active', true], ['orders.id', $id]]);
        $orders = $orders->leftjoin('branches', 'orders.branch_id', '=', 'branches.id')
                            ->leftjoin('csos', 'orders.cso_id', '=', 'csos.id')
                            ->select('orders.id', 'orders.code', 'orders.orderDate', 'orders.no_member', 'orders.name as customer_name', 'orders.phone as customer_phone', 'orders.city as customer_city', 'orders.address as customer_address','orders.product', 'orders.old_product as old_product', 'orders.prize as prize_product', 'orders.payment_type as payment_type', 'orders.total_payment as total_payment', 'orders.cash_upgrade as cash_upgrade', 'orders.down_payment as down_payment', 'orders.remaining_payment as remaining_payment', 'orders.bank as bank','branches.code as branch_code', 'orders.customer_type as customer_type','orders.30_cso_id as cso_30_id', 'orders.70_cso_id as cso_70_id','orders.description as description', 'orders.know_from as know_from', 'orders.distric','branches.id as branch_id', 'branches.code as branch_code', 'branches.name as branch_name', 'csos.code as cso_code', 'csos.name as cso_name')
                            ->get();
        $kota = $orders[0]->customer_city;
        if (strpos($kota, 'Kota') !== false) {
            $kota = str_replace('Kota ', '',$kota);
        }elseif (strpos($kota, 'Kabupaten') !== false){
            $kota = str_replace('Kabupaten ', '',$kota);
        }
        $city = RajaOngkir_City::where('city_name', 'like', '%'.$kota.'%')->first();

        foreach ($orders as $i => $doNya) {
            $tempId = json_decode($doNya['product'], true);
            $tempArray = $doNya['product'];
            $tempArray = [];
            foreach ($tempId as $j => $product) {
                $tempArray[$j] = [];
                if (gettype($product['id']) == gettype(1)){
                    $tempArray[$j]['id'] = $product['id'];
                }else {
                    $tempArray[$j]['id'] = 8;
                    $tempArray[$j]['name'] = $product['id'];
                }
                if(isset(DeliveryOrder::$Promo[$product['id']])){
                    $tempArray[$j]['name'] = DeliveryOrder::$Promo[$product['id']]['name'];
                }
                $tempArray[$j]['qty'] = $product['qty'];
            }
            $doNya['product'] = $tempArray;

            //khusus
            $cso_30 =  Cso::where('id', $doNya['cso_30_id'])->first();
            $cso_70 = Cso::where('id', $doNya['cso_70_id'])->first();
            $doNya['cso_30_code'] =$cso_30['code'];
            $doNya['cso_30_name'] =$cso_30['name'];
            $doNya['cso_70_code'] = $cso_70['code'];
            $doNya['cso_70_name'] =$cso_70['name'];
            $tempId = json_decode($doNya['bank'], true);
            $tempArray = [];
            foreach ($tempId as $j => $bank) {
                $tempArray[$j] = [];
                $tempArray[$j]['id'] = $bank['id'];
                $tempArray[$j]['name'] = Order::$Banks[$bank['id']];
                $tempArray[$j]['cicilan'] = $bank['cicilan'];
            }
            $doNya['bank'] = $tempArray;
            if($city == null){
                $doNya['province_id'] = 0;
            }else {
                $doNya['province_id'] = $city->province_id;
            }
            $doNya['district'] = array($doNya->getDistrict());
            $doNya['URL'] = route('order_success')."?code=".$doNya['code'];
        }
        $data = ['result' => 1,
                 'data' => $orders
                ];
        return response()->json($data, 200);
    }

    public function deleteApi(Request $request)
    {
        $messages = array(
            'id.required' => 'There\'s an error with the data.',
            'id.exists' => 'There\'s an error with the data.'
        );

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'exists:orders,id,active,1']
        ], $messages);

        if ($validator->fails()) {
            $data = [
                'result' => 0,
                'data' => $validator->errors(),
            ];

            return response()->json($data, 401);
        } else {
            $order = Order::find($request->id);
            $order->active = false;
            $order->save();

            // Menyimpan riwayat penghapusan ke tabel history_orders
            $historyOrder = [];
            $historyOrder["type_menu"] = "Order";
            $historyOrder["method"] = "Delete";
            $historyOrder["meta"] = [
                "user" => $request->user_id,
                "createdAt" => date("Y-m-d h:i:s"),
                'dataChange'=> $order->getChanges(),
            ];
            $historyOrder["user_id"] = $request->user_id;
            $historyOrder["menu_id"] = $request->id;

            HistoryUpdate::create($historyOrder);

            $data = [
                'result' => 1,
                'data' => $order,
            ];

            return response()->json($data, 200);
        }
    }

    public function fetchCustomer(Request $request)
    {
        $customer = Order::select(
                "name",
                "phone",
                "province",
                "city",
                "distric AS district",
                "address",
            )
            ->where("no_member", $request->no_member)
            ->orderBy("id", "desc")
            ->first();

        return response()->json(["data" => $customer]);
    }
}
