<?php

namespace App\Http\Controllers;

use App\Bank;
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
use App\OrderDetail;
use App\OrderPayment;
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
        $products = Product::where('active', true)->orderBy("code")->get();
        $branches = Branch::where('active', true)->orderBy("code", 'asc')->get();
        $csos = Cso::all();
        $cashUpgrades = Order::$CashUpgrade;
        $paymentTypes = Order::$PaymentType;
        $banks = Bank::all();
        $from_know = Order::$Know_From;
        return view('admin.add_order', compact('promos', 'products', 'branches', 'csos', 'from_know','cashUpgrades', 'paymentTypes', 'banks'));
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
            $data['province'] = $data['province_id'];
            $data['status'] = "new";
            
            $order = Order::create($data);

            //pembentukan array product
            $index = 0;
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if ($arrKey[0] == 'product') {
                    if (isset($arrKey[1]) && isset($data['qty_' . $arrKey[1]])) {
                        $orderDetail = new OrderDetail;
                        $orderDetail->order_id = $order['id'];
                        $orderDetail->type = OrderDetail::$Type['1'];
                        $orderDetail->qty = $data['qty_' . $arrKey[1]];
                        if ($value == 'other') {
                            $orderDetail->other = $data['product_other_' . $arrKey[1]];
                        } else {
                            $splitValue = explode("_", $value);
                            if ($splitValue[0] == "promo") {
                                $orderDetail->promo_id = $splitValue[1];
                            } else if ($splitValue[0] == "product") {
                                $orderDetail->product_id = $splitValue[1];
                            }
                        }
                        $orderDetail->save();
                        $index++;
                    }
                }
            }

            //pembentukan array old_product
            if (isset($data['old_product'])) {
                $orderDetail = new OrderDetail;
                $orderDetail->order_id = $order['id'];
                $orderDetail->type = OrderDetail::$Type['3'];
                $orderDetail->qty = $data['old_product_qty'] ?? 1;
                if ($data['old_product'] == "other") {
                    $orderDetail->other = $data['old_product_other'];
                } else {
                    $orderDetail->product_id = $data['old_product'];
                }
                $orderDetail->save();
            }

            //pembentukan array prize
            if (isset($data['prize'])) {
                $orderDetail = new OrderDetail;
                $orderDetail->order_id = $order['id'];
                $orderDetail->type = OrderDetail::$Type['2'];
                $orderDetail->qty = $data['prize_qty'] ?? 1;
                if ($data['prize'] == "other") {
                    $orderDetail->other = $data['prize_other'];
                } else {
                    $orderDetail->product_id = $data['prize'];
                }
                $orderDetail->save();
            }

            //pembentukan array Bank
            $index = 0;
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if($arrKey[0] == 'bank'){
                    if(isset($data['cicilan_'.$arrKey[1]])){
                        $orderPayment = new OrderPayment;
                        $orderPayment->order_id = $order['id'];
                        $orderPayment->total_payment = $data['downpayment_' . $arrKey[1]];
                        $orderPayment->payment_date = $data["orderDate"];
                        $orderPayment->bank_id = $data['bank_' . $arrKey[1]];
                        $orderPayment->cicilan = $data['cicilan_' . $arrKey[1]];

                        // save image
                        $arrImage = [];
                        $idxImg = 1;
                        for ($i = 0; $i < 3; $i++) {
                            if ($request->hasFile('images_' . $arrKey[1] . '_' . $i)) {
                                $path = "sources/order";
                                $file = $request->file('images_' . $arrKey[1] . '_' . $i);
                                $fileName = str_replace([' ', ':'], '', Carbon::now()->toDateTimeString()) . $arrKey[1] . $idxImg . "_order." . $file->getClientOriginalExtension();

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
                                $arrImage[] = $fileName;
                                $idxImg++;
                            }
                        }
                        $orderPayment->image = json_encode($arrImage);
                        $orderPayment->save();
                        $index++;
                    }
                }
            }


            // Set Order Down Payment
            $order->down_payment = OrderPayment::where("order_id", $order['id'])->sum('total_payment');
            $order->save();
            
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
        $csos = Cso::where('active', true)->orderBy('code')->get();
        $banks = Bank::all();
        $order = Order::where('code', $request['code'])->first();
        $csoDeliveryOrders = Cso::whereIn('id', json_decode($order['delivery_cso_id']) ?? [])->get();
        $order['district'] = $order->getDistrict();
        $historyUpdateOrder = HistoryUpdate::leftjoin('users','users.id', '=','history_updates.user_id' )
        ->select('history_updates.method', 'history_updates.created_at','history_updates.meta as meta' ,'users.name as name')
        ->where('type_menu', 'Order')->where('menu_id', $order->id)->get();
        return view('admin.detail_order', compact('order', 'historyUpdateOrder', 'csos', 'banks', 'csoDeliveryOrders'));
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
            $products = Product::where('active', true)->orderBy("code")->get();
            $branches = Branch::all()->sortBy("code");
            $csos = Cso::all();
            $cashUpgrades = Order::$CashUpgrade;
            $paymentTypes = Order::$PaymentType;
            $banks = Bank::all();
            $from_know = Order::$Know_From;

            $orderDetails['upgrade'] = OrderDetail::where('order_id', $orders['id'])
                ->where('type', OrderDetail::$Type['3'])->first();
            $orderDetails['prize'] = OrderDetail::where('order_id', $orders['id'])
                ->where('type', OrderDetail::$Type['2'])->first();
            $arr_price = [];
            return view('admin.update_order', compact('orders','promos', 'products', 'from_know','branches', 'csos', 'cashUpgrades', 'paymentTypes', 'banks', 'orderDetails', 'arr_price'));
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
            $orders['payment_type'] = $data['payment_type'];
            $orders['total_payment'] = $data['total_payment'];
            $orders['remaining_payment'] = $data['remaining_payment'];
            $orders['customer_type'] = $data['customer_type'];
            $orders['description'] = $data['description'];
            $orders['phone'] = $data['phone'];
            $orders['know_from'] = $data['know_from'];
            $orders['province'] = $data['province_id'];
            $orders['city'] = $data['city'];
            $orders['distric'] = $data['distric'];
            $orders->save();

            $orderDetails = OrderDetail::where("order_id", $orders["id"])->get();
            $orderPayments = OrderPayment::where("order_id", $orders["id"])->get();
            $orderDetailOlds = OrderDetail::where("order_id", $orders["id"])->get();
            $orderPaymentOlds = OrderPayment::where("order_id", $orders["id"])->get();

            //pembentukan array product
            $index = 0;
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if ($arrKey[0] == 'product') {
                    if (isset($arrKey[1]) && isset($data['qty_' . $arrKey[1]])) {
                        $isUpdateOrCreateProduct = true;
                        if (isset($data['orderdetailold'][$arrKey[1]])) {
                            $orderDetail = OrderDetail::find($data['orderdetailold'][$arrKey[1]]);
                            $orderDetail->product_id = null;
                            $orderDetail->promo_id = null;
                            $orderDetail->other = null;
                        } else {
                            if (isset($data['productold_'.$arrKey[1]])) {
                                $isUpdateOrCreateProduct = false;
                            }
                            $orderDetail = new OrderDetail;
                        }
                        if ($isUpdateOrCreateProduct) {
                            $orderDetail->order_id = $orders['id'];
                            $orderDetail->type = OrderDetail::$Type['1'];
                            $orderDetail->qty = $data['qty_' . $arrKey[1]];
                            if ($value == 'other') {
                                $orderDetail->other = $data['product_other_' . $arrKey[1]];
                            } else {
                                $splitValue = explode("_", $value);
                                if ($splitValue[0] == "promo") {
                                    $orderDetail->promo_id = $splitValue[1];
                                } else if ($splitValue[0] == "product") {
                                    $orderDetail->product_id = $splitValue[1];
                                }
                            }
                            $orderDetail->save();
                            $index++;
                        }
                    }
                }
            }

            // Hapus Old Order Detail Pembelian
            foreach ($orderDetails as $orderDetail) {
                if ($orderDetail['type'] == OrderDetail::$Type['1'] && !in_array($orderDetail['id'], $data['orderdetailold'])) {
                    $orderDetail->delete();
                }
            }

            //pembentukan array old_product
            if (isset($data['old_product'])) {
                // $orderDetail = OrderDetail::where("order_id", $orders['id'])
                //     ->where("type", OrderDetail::$Type['3'])->first();
                $orderDetail = $orderDetails->filter(function ($item) {
                    return $item->type == OrderDetail::$Type['3'];
                })->first();
                if (!$orderDetail) {
                    $orderDetail = new OrderDetail;
                }
                $orderDetail->product_id = null;
                $orderDetail->other = null;
                $orderDetail->order_id = $orders['id'];
                $orderDetail->type = OrderDetail::$Type['3'];
                $orderDetail->qty = $data['old_product_qty'] ?? 1;
                if ($data['old_product'] == "other") {
                    $orderDetail->other = $data['old_product_other'];
                } else {
                    $orderDetail->product_id = $data['old_product'];
                }
                $orderDetail->save();
            }

            //pembentukan array prize
            if (isset($data['prize'])) {
                // $orderDetail = OrderDetail::where("order_id", $orders['id'])
                //     ->where("type", OrderDetail::$Type['2'])->first();
                $orderDetail = $orderDetailOlds->filter(function ($item) {
                    return $item->type == OrderDetail::$Type['2'];
                })->first();
                if (!$orderDetail) {
                    $orderDetail = new OrderDetail;
                }
                $orderDetail->product_id = null;
                $orderDetail->other = null;
                $orderDetail->order_id = $orders['id'];
                $orderDetail->type = OrderDetail::$Type['2'];
                $orderDetail->qty = $data['prize_qty'] ?? 1;
                if ($data['prize'] == "other") {
                    $orderDetail->other = $data['prize_other'];
                } else {
                    $orderDetail->product_id = $data['prize'];
                }
                $orderDetail->save();
            }

            //pembentukan array Bank
            $index = 0;
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if($arrKey[0] == 'bank'){
                    if(isset($data['cicilan_'.$arrKey[1]])){
                        $isUpdateOrCreatePayment = true;
                        // Update Order Payment
                        if (isset($data['orderpaymentold'][$arrKey[1]])) {
                            $orderPayment = OrderPayment::find($data['orderpaymentold'][$arrKey[1]]);
                        } else {
                            if (isset($data['bankold_'.$arrKey[1]])) {
                                $isUpdateOrCreatePayment = false;
                            }
                            $orderPayment = new OrderPayment;
                        }
                        // Update Order Payment
                        if ($isUpdateOrCreatePayment) {
                            $orderPayment->order_id = $orders['id'];
                            $orderPayment->total_payment = $data['downpayment_' . $arrKey[1]];
                            $orderPayment->payment_date = $orders["orderDate"];
                            $orderPayment->bank_id = $data['bank_' . $arrKey[1]];
                            $orderPayment->cicilan = $data['cicilan_' . $arrKey[1]];
    
                            // save image
                            $arrImage = [];
                            $idxImg = 1;
                            for ($i = 0; $i < 3; $i++) {
                                $orderPaymentImages = json_decode($orderPayment->image, true) ?? [];
                                // Jika Hapus Gambar Lama
                                if (isset($orderPaymentImages[$i]) && isset($data['dltimg-'.$arrKey[1].'-'.$i])) {
                                    if (File::exists("sources/order/" . $orderPaymentImages[$i])) {
                                        File::delete("sources/order/" . $orderPaymentImages[$i]);
                                    }
                                    unset($orderPaymentImages[$i]);
                                }
                                if ($request->hasFile('images_' . $arrKey[1] . '_' . $i)) {
                                    $path = "sources/order";
    
                                    // Hapus Img Lama Jika Update Image
                                    if (isset($orderPaymentImages[$i])) {
                                        if (File::exists("sources/order/" . $orderPaymentImages[$i])) {
                                            File::delete("sources/order/" . $orderPaymentImages[$i]);
                                        }
                                    }
    
                                    $file = $request->file('images_' . $arrKey[1] . '_' . $i);
                                    $fileName = str_replace([' ', ':'], '', Carbon::now()->toDateTimeString()) . $arrKey[1] . $idxImg . "_order." . $file->getClientOriginalExtension();
    
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
                                    $arrImage[] = $fileName;
                                    $idxImg++;
                                } else if (isset($orderPaymentImages[$i])) {
                                    $arrImage[] = $orderPaymentImages[$i];
                                    $idxImg++;
                                }
                            }
                            $orderPayment->image = json_encode($arrImage);
                            $orderPayment->save();
                            $index++;
                        }
                    }
                }
            }

            // Hapus Old Order Payment
            foreach ($orderPayments as $orderPayment) {
                if (!in_array($orderPayment['id'], $data['orderpaymentold'])) {
                    $orderPaymentImages = json_decode($orderPayment->image, true);
                    foreach ($orderPaymentImages as $orderPaymentImage) {
                        if (File::exists("sources/order/" . $orderPaymentImage)) {
                            File::delete("sources/order/" . $orderPaymentImage);
                        }
                    }
                    $orderPayment->delete();
                }
            }            

            //update image
            // $arr_image_before = $orders['image'];
            // $namaGambar = [];
            // if($arr_image_before != null){
            //     $namaGambar = array_values($arr_image_before);
            // }
            // $idxImg = 1;

            // if ($request->hasFile('images0') || $request->hasFile('images1') || $request->hasFile('images2')){
            //     // Store image
            //     for ($i = 0; $i < $request->total_images; $i++) {
            //         if ($request->hasFile('images' . $i)) {
            //             if($arr_image_before != null){
            //                 if (array_key_exists($i, $arr_image_before)) {
            //                     if (File::exists("sources/order/" . $arr_image_before[$i])) {
            //                         File::delete("sources/order/" . $arr_image_before[$i]);
            //                     }
            //                 }
            //             }

            //             $path = "sources/order";
            //             $file = $request->file('images' . $i);
            //             $fileName = str_replace([' ', ':'], '', Carbon::now()->toDateTimeString()). $idxImg . "_order." . $file->getClientOriginalExtension();

            //             //compressed img
            //             $compres = Image::make($file->getRealPath());
            //             $compres->resize(540, null, function ($constraint) {
            //                 $constraint->aspectRatio();
            //             })->save($path.'/'.$fileName);

            //             //array_push($data['image'], $fileName);
            //             $namaGambar[$i] = $fileName;
            //             $idxImg++;
            //         } else {
            //             if($arr_image_before != null){
            //                 if (array_key_exists($i, $arr_image_before)) {
            //                     $namaGambar[$i] = $arr_image_before[$i];
            //                 }
            //             }
            //         }
            //     }
            // }

            // if ($request->dlt_img != "") {
            //     $deletes = explode(",", $request->dlt_img);
            //     foreach ($deletes as $value) {
            //         if (array_key_exists($value, $namaGambar)) {
            //             if (File::exists("sources/order/" . $codePath . "/" . $namaGambar[$value])) {
            //                 File::delete("sources/order/" . $codePath . "/" . $namaGambar[$value]);
            //             }
            //             unset($namaGambar[$value]);
            //         }
            //     }
            // }

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
            // $orders['image'] = $namaGambar;

            // Set Order Down Payment
            $orders->updateDownPayment();
            $orders->save();

            $dataChanges = array_diff(json_decode($orders, true), json_decode($dataBefore, true));
            $childs = ["orderDetail" => $orderDetailOlds, "orderPayment" => $orderPaymentOlds];
            foreach ($childs as $key => $child) {
                $orderChild = $orders->$key->keyBy('id');
                $child = $child->keyBy('id');
                foreach ($child as $i=>$c) {
                    $array_diff_c = isset($orderChild[$i]) 
                        ? array_diff(json_decode($orderChild[$i], true), json_decode($c, true)) 
                        : "deleted";
                    if ($array_diff_c == "deleted") {
                        $dataChanges[$key][$c['id']."_deleted"] = $c;
                    } else if ($array_diff_c) {
                        $dataChanges[$key][$c['id']] = $array_diff_c;
                    }
                }
                if ($orderChild > $child) {
                    $array_diff_c = array_diff($orderChild->pluck('id')->toArray(), $child->pluck('id')->toArray());
                    if ($array_diff_c) {
                        $dataChanges[$key]["added"] = $array_diff_c;
                    }
                }
            }

            $user = Auth::user();
            $historyUpdate['type_menu'] = "Order";
            $historyUpdate['method'] = "Update";
            $historyUpdate['meta'] = json_encode([
                'user'=>$user['id'],
                'createdAt' => date("Y-m-d h:i:s"),
                'dataChange'=> $dataChanges
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
        DB::beginTransaction();
        try{
            $order = Order::find($request->input('orderId'));
            $dataBefore = Order::find($request->input('orderId'));
            $last_status_order = $order->status;
            $order->status = $request->input('status_order');
            
            // Save Delivery CSO
            if ($order->status == Order::$status['3']) {
                $order->delivery_cso_id = json_encode($request->delivery_cso_id);
            }        
            $order->save();
            
            $user = Auth::user();
            $historyUpdate['type_menu'] = "Order";
            $historyUpdate['method'] = "Update Status";
            $historyUpdate['meta'] = json_encode([
                'user'=>$user['id'],
                'createdAt' => date("Y-m-d h:i:s"),
                'dataChange'=> array_diff(json_decode($order, true), json_decode($dataBefore,true))
            ]);

            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $order->id;
            $createData = HistoryUpdate::create($historyUpdate);
            
            DB::commit();
            return redirect()->back()->with('success', 'Status Order Berhasil Di Ubah');
        }catch(\Exception $ex){
            DB::rollback();
            return redirect()->back()->withErrors($ex->getMessage());
        }
    }

    public function storeOrderPayment(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'total_payment' => 'required',
                'payment_date' => 'required',
                'bank_id' => 'required|exists:banks,id',
                'cicilan' => 'required',
            ]);

            $data = $request->all();
            $orderPayment = new OrderPayment;
            $orderPayment->order_id = $data['order_id'];
            $orderPayment->total_payment = $data['total_payment'];   
            $orderPayment->payment_date = $data['payment_date'];
            $orderPayment->bank_id = $data['bank_id'];
            $orderPayment->cicilan = $data['cicilan'];

            // save image
            $arrImage = [];
            $idxImg = 1;
            foreach ($request->file("images") as $image) {
                $path = "sources/order";
                $file = $image;
                $fileName = str_replace([' ', ':'], '', Carbon::now()->toDateTimeString()) . $idxImg . "_order." . $file->getClientOriginalExtension();

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
                $arrImage[] = $fileName;
            }
            $orderPayment->image = json_encode($arrImage);
            $orderPayment->save();

            $order = Order::find($data['order_id']);
            $order->updateDownPayment();
            $order->save();

            $user = Auth::user();
            $historyUpdate['type_menu'] = "Order";
            $historyUpdate['method'] = "Update";
            $historyUpdate['meta'] = json_encode([
                'user'=>$user['id'],
                'createdAt' => date("Y-m-d h:i:s"),
                'dataChange'=> ["Add Order Payment" => $orderPayment]
            ]);

            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $data['order_id'];
            $createData = HistoryUpdate::create($historyUpdate);
            DB::commit();

            return redirect()->back()->with('success', 'Payment Berhasil Di Tambah');
        }catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' =>  $ex->getMessage(), 500]);
        }
    }

    public function editOrderPayment(Request $request)
    {
        if($request->has('order_id') && $request->has('order_payment_id')){
            $orderPayment = OrderPayment::where('order_id', $request->get('order_id'))
                ->where('id', $request->get('order_payment_id'))->first();

            if ($orderPayment) {
                return response()->json([
                    'status' => 'success',
                    'result' => $orderPayment
                ]);
            }
        }
        return response()->json(['result' => 'Gagal!!']);
    }

    public function updateOrderPayment(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->has('order_id') && $request->has('order_payment_id')) {
                $orderPayment = OrderPayment::where('order_id', $request->get('order_id'))
                    ->where('id', $request->get('order_payment_id'))->first();
                    
                if ($orderPayment) {
                    $orderPaymentOld = OrderPayment::where('order_id', $request->get('order_id'))
                        ->where('id', $request->get('order_payment_id'))->first();

                    $data = $request->all();
                    $orderPayment->total_payment = $data['total_payment'];
                    $orderPayment->payment_date = $data['payment_date'];
                    $orderPayment->bank_id = $data['bank_id'];
                    $orderPayment->cicilan = $data['cicilan'];

                    // save image
                    $arrImage = [];
                    $idxImg = 1;
                    for ($i = 0; $i < 3; $i++) {
                        $orderPaymentImages = json_decode($orderPayment->image, true) ?? [];
                        // Jika Hapus Gambar Lama
                        if (isset($orderPaymentImages[$i]) && isset($data['dltimg-'.$i])) {
                            if (File::exists("sources/order/" . $orderPaymentImages[$i])) {
                                File::delete("sources/order/" . $orderPaymentImages[$i]);
                            }
                            unset($orderPaymentImages[$i]);
                        }
                        if ($request->hasFile('images_' . $i)) {
                            $path = "sources/order";

                            // Hapus Img Lama Jika Update Image
                            if (isset($orderPaymentImages[$i])) {
                                if (File::exists("sources/order/" . $orderPaymentImages[$i])) {
                                    File::delete("sources/order/" . $orderPaymentImages[$i]);
                                }
                            }

                            $file = $request->file('images_' . $i);
                            $fileName = str_replace([' ', ':'], '', Carbon::now()->toDateTimeString()) . $idxImg . "_order." . $file->getClientOriginalExtension();

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
                            $arrImage[] = $fileName;
                            $idxImg++;
                        } else if (isset($orderPaymentImages[$i])) {
                            $arrImage[] = $orderPaymentImages[$i];
                            $idxImg++;
                        }
                    }
                    $orderPayment->image = json_encode($arrImage);
                    $orderPayment->save();

                    // Set Order Down Payment
                    $order = Order::find($data['order_id']);
                    $order->updateDownPayment();
                    $order->save();

                    $user = Auth::user();
                    $historyUpdate['type_menu'] = "Order";
                    $historyUpdate['method'] = "Update";
                    $historyUpdate['meta'] = json_encode([
                        'user'=>$user['id'],
                        'createdAt' => date("Y-m-d h:i:s"),
                        'dataChange'=> ["Update Order Payment: " => [$orderPayment->id => array_diff(json_decode($orderPayment, true), json_decode($orderPaymentOld, true)) ]]
                    ]);

                    $historyUpdate['user_id'] = $user['id'];
                    $historyUpdate['menu_id'] = $data['order_id'];
                    $createData = HistoryUpdate::create($historyUpdate);
                    DB::commit();

                    return redirect()->back()->with('success', 'Order Payment Berhasil Di Ubah');
                }
            }
            return response()->json(['result' => 'Gagal!!']);
        }catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' =>  $ex->getMessage(), 500]);
        }
    }

    public function updateStatusOrderPayment(Request $request)
    {
        if ($request->has('order_id') && $request->has('order_payment_id')) {
            $orderPayment = OrderPayment::where('order_id', $request->get('order_id'))
                ->where('id', $request->get('order_payment_id'))->first();
    
            if ($orderPayment) {
                DB::beginTransaction();
                try{
                    $data = $request->all();
                    $orderPayment_status = $request->get('status_acc');
                    if ($orderPayment_status =='true') {
                        $orderPayment->status = 'verified';
                    } else if($orderPayment_status == 'false') {
                        $orderPayment->status = 'rejected';
                    }
                    $orderPayment->save();

                    // Set Order Down Payment
                    $order = Order::find($data['order_id']);
                    $order->updateDownPayment();
                    $order->save();

                    $user = Auth::user();
                    $historyUpdate['type_menu'] = "Order";
                    $historyUpdate['method'] = "Update";
                    $historyUpdate['meta'] = json_encode([
                        'user'=>$user['id'],
                        'createdAt' => date("Y-m-d h:i:s"),
                        'dataChange'=> ["Update Status Order Payment: " => [$orderPayment->id => $orderPayment->status ]]
                    ]);

                    $historyUpdate['user_id'] = $user['id'];
                    $historyUpdate['menu_id'] = $data['order_id'];
                    $createData = HistoryUpdate::create($historyUpdate);
                    
                    DB::commit();
                    return redirect()->back()->with('success', 'Order Payment Berhasil Di Ubah');
                }catch(\Exception $ex){
                    DB::rollback();
                    return redirect()->back()->withErrors($ex->getMessage());
                }
            }
        }
        return response()->json(['result' => 'Gagal!!']);
    }

    public function deleteOrderPayment(Request $request)
    {
        DB::beginTransaction();
        try {
            if($request->has('id')){
                $orderPayment = OrderPayment::find($request->get('id'));

                if ($orderPayment) {
                    $data['order_id'] = $orderPayment->order_id;
                    $orderPaymentOld = OrderPayment::find($request->get('id'));

                    $orderPaymentImages = json_decode($orderPayment->image, true);
                    foreach ($orderPaymentImages as $orderPaymentImage) {
                        if (File::exists("sources/order/" . $orderPaymentImage)) {
                            File::delete("sources/order/" . $orderPaymentImage);
                        }
                    }
                    $orderPayment->delete();

                    // Set Order Down Payment
                    $order = Order::find($data['order_id']);
                    $order->updateDownPayment();
                    $order->save();

                    $user = Auth::user();
                    $historyUpdate['type_menu'] = "Order";
                    $historyUpdate['method'] = "Update";
                    $historyUpdate['meta'] = json_encode([
                        'user'=>$user['id'],
                        'createdAt' => date("Y-m-d h:i:s"),
                        'dataChange'=> ["Deleted Order Payment" => $orderPaymentOld ]
                    ]);

                    $historyUpdate['user_id'] = $user['id'];
                    $historyUpdate['menu_id'] = $data['order_id'];
                    $createData = HistoryUpdate::create($historyUpdate);
                    DB::commit();

                    return redirect()->back()->with('success', 'Order Payment Berhasil Di Hapus');
                }
            }
            
            return response()->json(['result' => 'Gagal!!']);
        }catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' =>  $ex->getMessage(), 500]);
        }
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

            foreach ($orders as $order) {
                $orderDetail = OrderDetail::where('type', OrderDetail::$Type['1'])
                    ->where('order_id', $order->id)->first();
                $order->product = $orderDetail->productNamenya();
                $order->orderDetailQty = $orderDetail->qty;
            }

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

        $query_total_sale_untilYesterday = "SELECT SUM(op.total_payment) 
            FROM order_payments as op
            LEFT JOIN orders as o
            ON o.id = op.order_id
            WHERE o.branch_id = b.id
            AND op.payment_date >= '$startDate'
            AND op.payment_date <= '$yesterdayDate'
            AND op.status = 'verified'
            AND (o.status = '" . Order::$status['2'] . "'
            OR o.status = '" . Order::$status['3'] . "' 
            OR o.status = '" . Order::$status['4'] . "')";
        $query_total_sale_today = "SELECT SUM(op.total_payment) 
            FROM order_payments as op
            LEFT JOIN orders as o
            ON o.id = op.order_id
            WHERE o.branch_id = b.id
            AND op.payment_date = '$endDate'
            AND op.status = 'verified'
            AND (o.status = '" . Order::$status['2'] . "' 
            OR o.status = '" . Order::$status['3'] . "'
            OR o.status = '" . Order::$status['4'] . "')";

        // $query_total_sale_untilYesterday = "SELECT SUM(o.down_payment) 
        //     FROM orders as o
        //     WHERE o.branch_id = b.id
        //     AND o.orderDate >= '$startDate'
        //     AND o.orderDate <= '$yesterdayDate'
        //     AND (o.status = '" . Order::$status['2'] . "'
        //     OR o.status = '" . Order::$status['3'] . "' 
        //     OR o.status = '" . Order::$status['4'] . "')";
        // $query_total_sale_today = "SELECT SUM(o.down_payment) 
        //     FROM orders as o
        //     WHERE o.branch_id = b.id
        //     AND o.orderDate = '$endDate'
        //     AND (o.status = '" . Order::$status['2'] . "' 
        //     OR o.status = '" . Order::$status['3'] . "'
        //     OR o.status = '" . Order::$status['4'] . "')";

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

        $query_total_sale_untilYesterday = "SELECT SUM(op.total_payment) 
            FROM order_payments as op
            LEFT JOIN orders as o
            ON o.id = op.order_id
            WHERE o.cso_id = c.id
            AND op.payment_date >= '$startDate'
            AND op.payment_date <= '$yesterdayDate'
            AND op.status = 'verified'
            AND (o.status = '" . Order::$status['2'] . "'
            OR o.status = '" . Order::$status['3'] . "' 
            OR o.status = '" . Order::$status['4'] . "')";
        $query_total_sale_today = "SELECT SUM(op.total_payment) 
            FROM order_payments as op
            LEFT JOIN orders as o
            ON o.id = op.order_id
            WHERE o.cso_id = c.id
            AND op.payment_date = '$endDate'
            AND op.status = 'verified'
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
        
        $order_reports = Order::leftjoin('order_payments', 'orders.id', '=', 'order_payments.order_id')
            ->where('order_payments.payment_date', '>=', $startDate)
            ->where('order_payments.payment_date', '<=', $endDate);

        $currentBranch = null;
        if ($request->has('filter_branch')) {
            $currentBranch = Branch::find($request->filter_branch);
            $order_reports->where('orders.branch_id', $currentBranch['id']);
        }
        $currentCso = null;
        if ($request->has('filter_cso')) {
            $currentCso = Cso::where("code", $request->filter_cso)->first();
            $order_reports->where('cso_id', $currentCso['id']);
        }

        $order_reports = $order_reports->where(function($query) {
                $query->where('orders.status', Order::$status['2'])
                    ->orWhere('orders.status', Order::$status['3'])
                    ->orWhere('orders.status', Order::$status['4']);
            })
            ->where('order_payments.status', 'verified')
            ->orderBy('order_payments.payment_date', 'desc')->select('orders.*', DB::raw('SUM(order_payments.total_payment) as totalPaymentNya'))->groupBy('orders.id')->get();
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

        $query_total_sale_untilYesterday = "SELECT SUM(o.down_payment) 
            FROM orders as o
            WHERE o.branch_id = b.id
            AND o.orderDate >= '$startDate'
            AND o.orderDate <= '$yesterdayDate'
            AND (o.status = '" . Order::$status['2'] . "' 
            OR o.status = '" . Order::$status['3'] . "'
            OR o.status = '" . Order::$status['4'] . "')";
        $query_total_sale_today = "SELECT SUM(o.down_payment) 
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

        $query_total_sale_untilYesterday = "SELECT SUM(o.down_payment) 
            FROM orders as o
            WHERE o.cso_id = c.id
            AND o.orderDate >= '$startDate'
            AND o.orderDate <= '$yesterdayDate'
            AND (o.status = '" . Order::$status['2'] . "' 
            OR o.status = '" . Order::$status['3'] . "'
            OR o.status = '" . Order::$status['4'] . "')";
        $query_total_sale_today = "SELECT SUM(o.down_payment) 
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
        $split = explode("_", $promo_id);
        if ($split[0] == "promo") {
            $promos = Promo::find($split[1]);
        } else if ($split[0] == "product") {
            $promos = Product::find($split[1]);
        }
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
        return response()->json("Error! Update Fitur Order", 400);

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
            //pembentukan array old_product
            if (isset($data['old_product']) && $data['old_product']) {
                $data['old_product'] = json_encode([
                    "name" => $data['old_product'],
                    "qty" => $data['old_product_qty'] ?? 1
                ]);
            }
            //pembentukan array prize
            if (isset($data['prize']) && $data['prize']) {
                $data['prize'] = json_encode([
                    "name" => $data['prize'],
                    "qty" => $data['prize_qty'] ?? 1
                ]);
            }
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
        return response()->json("Error! Update Fitur Order", 400);

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
            //pembentukan array old_product
            if (isset($data['old_product']) && $data['old_product']) {
                $data['old_product'] = json_encode([
                    "name" => $data['old_product'],
                    "qty" => $data['old_product_qty'] ?? 1
                ]);
            }
            //pembentukan array prize
            if (isset($data['prize']) && $data['prize']) {
                $data['prize'] = json_encode([
                    "name" => $data['prize'],
                    "qty" => $data['prize_qty'] ?? 1
                ]);
            }
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
