<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\DeliveryOrder;
use App\Branch;
use App\Cso;
use App\User;
use Illuminate\Validation\Rule;
use Validator;
use App\RajaOngkir_City;
use App\HistoryUpdate;
use App\CategoryProduct;
use DB;
use App\Utils;

class DeliveryOrderController extends Controller
{
	//Buat menampilkan form DO
    public function index()
    {
    	$promos = DeliveryOrder::$Promo;
    	$branches = Branch::all();
        $csos = Cso::all();
        $categoryProducts = CategoryProduct::all();
        return view('deliveryorder', compact('promos', 'branches', 'csos', 'categoryProducts'));
    }

    public function store(Request $request){

    	$data = $request->all();
    	$data['code'] = "DO_BOOK/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);
    	$data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];

    	//pembentukan array product
		$data['arr_product'] = [];
    	foreach ($data as $key => $value) {
    		$arrKey = explode("_", $key);
    		if($arrKey[0] == 'product'){
    			if(isset($data['qty_'.$arrKey[1]])){
    				$data['arr_product'][$key] = [];
    				$data['arr_product'][$key]['id'] = $value;

                    // {{-- KHUSUS Philiphin --}}
                    if($value == 'other'){
                        $data['arr_product'][$key]['id'] = $data['product_other_'.$arrKey[1]];
                    }
                    //===========================

    				$data['arr_product'][$key]['qty'] = $data['qty_'.$arrKey[1]];
    			}
    		}
    	}
    	$data['arr_product'] = json_encode($data['arr_product']);

        $deliveryOrder = DeliveryOrder::create($data);

        $phone = preg_replace('/[^A-Za-z0-9\-]/', '', $deliveryOrder['phone']);
        if($phone[0]==0 || $phone[0]=="0"){
           $phone =  substr($phone, 1);
        }
        $phone = "62".$phone;
        $code = $deliveryOrder['code'];
        $url = "https://waki-indonesia.co.id/register-success?code=".$code."";
        Utils::sendSms($phone, "Terima kasih telah melakukan registrasi di WAKi Indonesia. Berikut link detail registrasi anda (".$url."). Info lebih lanjut, hubungi 082138864962.");
    	return redirect()->route('successorder', ['code'=>$deliveryOrder['code']]);
    }

    public function successorder(Request $request){
    	$deliveryOrder = DeliveryOrder::where('code', $request['code'])->first();
        $categoryProducts = CategoryProduct::all();
        return view('ordersuccess', compact('deliveryOrder', 'categoryProducts'));
    }

    public function listDeliveryOrder(Request $request){
        $deliveryOrders = DeliveryOrder::all();
        return view('templistregwaki1995', compact('deliveryOrders'));
    }

    public function admin_AddDeliveryOrder(){
        $promos = DeliveryOrder::$Promo;
        $branches = Branch::where('active', true)->get();
        $csos = Cso::all();
        return view('admin.add_deliveryorder', compact('promos', 'branches', 'csos'));
    }

    public function admin_StoreDeliveryOrder(Request $request){
        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['code'] = "DO_BOOK/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];

            //pembentukan array product
            $data['arr_product'] = [];
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if($arrKey[0] == 'product'){
                    if(isset($data['qty_'.$arrKey[1]])){
                        $data['arr_product'][$key] = [];
                        $data['arr_product'][$key]['id'] = $value;

                        // {{-- KHUSUS Philiphin --}}
                        if($value == 'other'){
                            $data['arr_product'][$key]['id'] = $data['product_other_'.$arrKey[1]];
                        }
                        //===========================

                        $data['arr_product'][$key]['qty'] = $data['qty_'.$arrKey[1]];
                    }
                }
            }
            // $data['arr_product'] = [];
            // foreach ($data as $key => $value) {
            //     $arrKey = explode("_", $key);
            //     if($arrKey[0] == 'product'){
            //         if(isset($data['qty_'.$arrKey[1]])){
            //             $data['arr_product'][$key] = [];
            //             $data['arr_product'][$key]['id'] = $value;
            //             $data['arr_product'][$key]['qty'] = $data['qty_'.$arrKey[1]];
            //         }
            //     }
            // }
            $data['arr_product'] = json_encode($data['arr_product']);

            $deliveryOrder = DeliveryOrder::create($data);

            DB::commit();

            $phone = preg_replace('/[^A-Za-z0-9\-]/', '', $deliveryOrder['phone']);
            if($phone[0]==0 || $phone[0]=="0"){
               $phone =  substr($phone, 1);
            }
            $phone = "62".$phone;
            $code = $deliveryOrder['code'];
            $url = "https://waki-indonesia.co.id/register-success?code=".$code."";
            Utils::sendSms($phone, "Terima kasih telah melakukan registrasi di WAKi Indonesia. Berikut link detail registrasi anda (".$url."). Info lebih lanjut, hubungi 082138864962.");
            return redirect()->route('detail_deliveryorder', ['code'=>$deliveryOrder['code']]);
            //return response()->json(['success' => route('detail_deliveryorder', ['code'=>$deliveryOrder['code']])]);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['errors' => $ex]);
        }
        
    }

    public function admin_ListDeliveryOrder(Request $request){
        $url = $request->all();
        $branches = Branch::Where('active', true)->get();

        //khususu head-manager, head-admin, admin
        $deliveryOrders = DeliveryOrder::where('delivery_orders.active', true);
        $countDeliveryOrders = DeliveryOrder::count();

        //khusus akun CSO
        if(Auth::user()->roles[0]['slug'] == 'cso'){
            $deliveryOrders = Auth::user()->cso->deliveryOrder;
        }

        //khusus akun branch dan area-manager
        if(Auth::user()->roles[0]['slug'] == 'branch' || Auth::user()->roles[0]['slug'] == 'area-manager'){
            $arrbranches = [];
            foreach (Auth::user()->listBranches() as $value) {
                array_push($arrbranches, $value['id']);
            }
            $deliveryOrders = DeliveryOrder::WhereIn('branch_id', $arrbranches)->get();
        }
        if($request->has('filter_branch') && Auth::user()->roles[0]['slug'] != 'branch'){
            $deliveryOrders = $deliveryOrders->where('branch_id', $request->filter_branch);
        }
        if($request->has('filter_cso') && Auth::user()->roles[0]['slug'] != 'cso'){
            $deliveryOrders = $deliveryOrders->where('cso_id', $request->filter_cso);
        }
        $deliveryOrders = $deliveryOrders->paginate(10);
        return view('admin.list_deliveryorder', compact('deliveryOrders', 'countDeliveryOrders', 'branches','url'));
    }

    public function admin_DetailDeliveryOrder(Request $request){
        $deliveryOrder = DeliveryOrder::where('code', $request['code'])->first();
        $historyUpdateDeliveryOrder = HistoryUpdate::leftjoin('users','users.id', '=','history_updates.user_id' )
        ->select('history_updates.method', 'history_updates.created_at','history_updates.meta as meta' ,'users.name as name')
        ->where('type_menu', 'Delivery Order')->where('menu_id', $deliveryOrder->id)->get();
        return view('admin.detail_deliveryorder', compact('deliveryOrder', 'historyUpdateDeliveryOrder'));
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
            $deliveryOrders = DeliveryOrder::find($request->get('id'));
            $promos = DeliveryOrder::$Promo;
            $branches = Branch::all();
            $csos = Cso::all();

            return view('admin.update_deliveryorder', compact('deliveryOrders', 'promos', 'branches', 'csos'));
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
        $deliveryOrders = DeliveryOrder::find($request->input('idDeliveryOrder'));
        $dataBefore = DeliveryOrder::find($request->input('idDeliveryOrder'));

        DB::beginTransaction();
        try {
            $deliveryOrders->no_member = $request->input('no_member');
            $deliveryOrders->name = $request->input('name');
            $deliveryOrders->address = $request->input('address');
            $deliveryOrders->phone = $request->input('phone');

            //pembentukan array product
            $data = $request->all();
            $data['arr_product'] = [];
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if($arrKey[0] == 'product'){
                    if(isset($data['qty_'.$arrKey[1]])){
                        $data['arr_product'][$key] = [];
                        $data['arr_product'][$key]['id'] = $value;
                        $data['arr_product'][$key]['qty'] = $data['qty_'.$arrKey[1]];
                    }
                }
            }
            $deliveryOrders->arr_product = json_encode($data['arr_product']);

            $deliveryOrders->cso_id = $request->input('idCSO');
            $deliveryOrders->branch_id = $request->input('branch_id');
            $deliveryOrders->city = $request->input('city');
            
            $deliveryOrders->save();

            $user = Auth::user();
            $historyUpdate= [];
            $historyUpdate['type_menu'] = "Delivery Order";
            $historyUpdate['method'] = "Update";
            $historyUpdate['meta'] = json_encode(['user'=>$user['id'],'createdAt' => date("Y-m-d h:i:s"),'dataChange'=> array_diff(json_decode($deliveryOrders, true), json_decode($dataBefore,true))]);
            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $deliveryOrders->id;

            $createData = HistoryUpdate::create($historyUpdate);

            DB::commit();
            return response()->json(['success' => 'Berhasil!!']);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' => $ex->getMessage()], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete($id) {
        DB::beginTransaction();
        try{
            $deliveryOrder = DeliveryOrder::where('id', $id)->first();
            $deliveryOrder->active = false;
            $deliveryOrder->save();

            $user = Auth::user();
            $historyUpdate= [];
            $historyUpdate['type_menu'] = "Delivery Order";
            $historyUpdate['method'] = "Delete";
            $historyUpdate['meta'] = json_encode(['user'=>$user['id'],'createdAt' => date("Y-m-d h:i:s"), 'dateChange'=> json_encode(array('Active'=>$deliveryOrder->active))]);
            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $id;

            $createData = HistoryUpdate::create($historyUpdate);
            DB::commit();
            return redirect()->route('list_deliveryorder')->with('success', 'Data Berhasil Di Hapus');
        }catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' =>  $ex->getMessage(), 500]);
        }
    }

    //KHUSUS API APPS
    public function fetchPromosApi(){
        $data = DeliveryOrder::$Promo;
        $promos = [];
        foreach ($data as $key => $value) {
            $value['id'] = $key;
            array_push($promos, $value);
        }
        
        $other = ['id'=>sizeof($data)+1, 'name'=>"OTHER", 'harga'=>"-"];
        array_push($promos, $other);

        $data = ['result' => 1,
                     'data' => $promos
                    ];
            return response()->json($data, 200);
    }

    public function addApi(Request $request)
    {
        $messages = array(
                'cso_id.required' => 'The CSO Code field is required.',
                'cso_id.exists' => 'Wrong CSO Code.',
                'branch_id.required' => 'The Branch must be selected.',
                'branch_id.exists' => 'Please choose the branch.',
            );

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'cso_id' => ['required', 'exists:csos,code'],
            'branch_id' => ['required', 'exists:branches,id'],
            'product_0' => 'required',
            'qty_0' => 'required'
        ], $messages);

        if ($validator->fails()){
            $data = ['result' => 0,
                     'data' => $validator->errors()
                    ];
            return response()->json($data, 401);
        }
        else{
            $data = $request->all();
            $data['code'] = "DO_BOOK/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];

            //pembentukan array product
            $data['arr_product'] = [];
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if($arrKey[0] == 'product'){
                    if(isset($data['qty_'.$arrKey[1]])){
                        $data['arr_product'][$key] = [];
                        $data['arr_product'][$key]['id'] = $value;

                        // {{-- KHUSUS Philiphin --}}
                        if($value == 'other'){
                            $data['arr_product'][$key]['id'] = $data['product_other_'.$arrKey[1]];
                        }
                        //===========================

                        $data['arr_product'][$key]['qty'] = $data['qty_'.$arrKey[1]];
                    }
                }
            }
            $data['arr_product'] = json_encode($data['arr_product']);

            $deliveryOrder = DeliveryOrder::create($data);
            $deliveryOrder['URL'] = route('successorder')."?code=".$deliveryOrder['code'];

            $data = ['result' => 1,
                     'data' => $deliveryOrder
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

        $validator = \Validator::make($request->all(), [
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
            $deliveryOrders = DeliveryOrder::where('delivery_orders.active', true);

            //khusus akun CSO
            if($userNya->roles[0]['slug'] == 'cso'){
                $csoIdUser = $userNya->cso['id'];
                $deliveryOrders = DeliveryOrder::where([['delivery_orders.active', true], ['delivery_orders.cso_id', $csoIdUser]]);
            }

            //khusus akun branch dan area-manager
            if($userNya->roles[0]['slug'] == 'branch' || $userNya->roles[0]['slug'] == 'area-manager'){
                $arrbranches = [];
                foreach ($userNya->listBranches() as $value) {
                    array_push($arrbranches, $value['id']);
                }
                $deliveryOrders = DeliveryOrder::WhereIn('delivery_orders.branch_id', $arrbranches)->where('delivery_orders.active', true);
            }

            $deliveryOrders = $deliveryOrders->leftjoin('branches', 'delivery_orders.branch_id', '=', 'branches.id')
                                ->leftjoin('csos', 'delivery_orders.cso_id', '=', 'csos.id')
                                ->select('delivery_orders.id', 'delivery_orders.code', 'delivery_orders.created_at', 'delivery_orders.name as customer_name', 'delivery_orders.arr_product', 'branches.code as branch_code', 'branches.name as branch_name', 'csos.code as cso_code', 'csos.name as cso_name');
            if($request->has('filter_branch')){
                $deliveryOrders = $deliveryOrders->where('delivery_orders.branch_id', $request->filter_branch);
            }
            if($request->has('filter_cso')){
                $deliveryOrders = $deliveryOrders->where('delivery_orders.cso_id', $request->filter_cso);
            }
            if($request->has('filter_city')){
                $deliveryOrders = $deliveryOrders->where('delivery_orders.city', 'like', '%'.$request->filter_city.'%');
            }
            if($request->has('filter_startDate')&& $request->has('filter_endDate')){
                $deliveryOrders = $deliveryOrders->whereBetween('delivery_orders.created_at', [$request->filter_startDate.' 00:00:00', $request->filter_endDate.' 23:59:59']);
            }
            $deliveryOrders = $deliveryOrders->orderBy('created_at', 'DESC');                     
            $deliveryOrders = $deliveryOrders->paginate($request->limit);

            foreach ($deliveryOrders as $i => $doNya) {
                $tempId = json_decode($doNya['arr_product'], true);
                $tempArray = $doNya['arr_product'];
                $tempArray = [];
                foreach ($tempId as $j => $product) {
                    $tempArray2 = [];
                    $tempArray2['name'] = $product['id'];
                    if(isset(DeliveryOrder::$Promo[$product['id']])){
                        $tempArray2['name'] = DeliveryOrder::$Promo[$product['id']]['name'];
                    }
                    $tempArray2['qty'] = $product['qty'];
                    array_push($tempArray, $tempArray2);
                }
                $doNya['arr_product'] = $tempArray;
            }

            $data = ['result' => 1,
                     'data' => $deliveryOrders
                    ];
            return response()->json($data, 200);
        }        
    }

    public function updateApi(Request $request)
    {
        $messages = array(
                'cso_id.required' => 'The CSO Code field is required.',
                'cso_id.exists' => 'Wrong CSO Code.',
                'branch_id.required' => 'The Branch must be selected.',
                'branch_id.exists' => 'Please choose the branch.',
            );

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'cso_id' => ['required', 'exists:csos,code'],
            'branch_id' => ['required', 'exists:branches,id'],
            'product_0' => 'required',
            'qty_0' => 'required'
        ], $messages);

        if ($validator->fails()){
            $data = ['result' => 0,
                     'data' => $validator->errors()
                    ];
            return response()->json($data, 401);
        }
        else{
            $data = $request->all();
            $data['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];

            //pembentukan array product
            $data['arr_product'] = [];
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if($arrKey[0] == 'product'){
                    if(isset($data['qty_'.$arrKey[1]])){
                        $data['arr_product'][$key] = [];
                        $data['arr_product'][$key]['id'] = $value;

                        // {{-- KHUSUS Philiphin --}}
                        if($value == 'other'){
                            $data['arr_product'][$key]['id'] = $data['product_other_'.$arrKey[1]];
                        }
                        //===========================

                        $data['arr_product'][$key]['qty'] = $data['qty_'.$arrKey[1]];
                    }
                }
            }
            $data['arr_product'] = json_encode($data['arr_product']);

            $deliveryOrder = DeliveryOrder::find($data['id']);
            $deliveryOrder->fill($data)->save();

            $deliveryOrder['URL'] = route('successorder')."?code=".$deliveryOrder['code'];

            $data = ['result' => 1,
                     'data' => $deliveryOrder
                    ];
            return response()->json($data, 200);
        }
    }

    public function viewApi($id)
    {
        $delivery_orders = DeliveryOrder::where([['delivery_orders.active', true], ['delivery_orders.id', $id]]);
        $delivery_orders = $delivery_orders->leftjoin('branches', 'delivery_orders.branch_id', '=', 'branches.id')
                            ->leftjoin('csos', 'delivery_orders.cso_id', '=', 'csos.id')
                            ->select('delivery_orders.id','delivery_orders.city as city', 'delivery_orders.code', 'delivery_orders.created_at', 'delivery_orders.no_member as no_member', 'delivery_orders.name as customer_name', 'delivery_orders.phone as customer_phone', 'delivery_orders.address as customer_address', 'delivery_orders.city as city', 'delivery_orders.arr_product', 'branches.id as branch_id','branches.code as branch_code', 'branches.name as branch_name', 'csos.code as cso_code', 'csos.name as cso_name')
                            ->get();

        $kota = $delivery_orders[0]->city; 
        if (strpos($kota, 'Kota') !== false) {
            $kota = str_replace('Kota ', '',$kota);
        }elseif (strpos($kota, 'Kabupaten') !== false){
            $kota = str_replace('Kabupaten ', '',$kota);
        }                    
        $city = RajaOngkir_City::where('city_name', 'like', '%'.$kota.'%')->first();
        foreach ($delivery_orders as $i => $doNya) {
            $tempId = json_decode($doNya['arr_product'], true);
            $tempArray = $doNya['arr_product'];
            $tempArray = [];
            // dd($tempId);
            foreach ($tempId as $j => $product) {
                $tempArray2 = [];
                if(is_numeric($product['id'])){
                    $prod = DeliveryOrder::$Promo[$product['id']];
                    if ($prod != null){
                        $tempArray2['id'] = $product['id'];
                        $tempArray2['name'] = $prod['name'];
                    }
                }else{
                    $tempArray2['id'] = 8;
                    $tempArray2['name'] = $product['id'];
                }
                               
                $tempArray2['qty'] = $product['qty'];
                array_push($tempArray, $tempArray2);
            }
            $doNya['arr_product'] = $tempArray;
            if($city == null){
                $doNya['province_id'] = 0;
            }else {
                $doNya['province_id'] = $city->province_id;
            }
            $doNya['URL'] = route('successorder')."?code=".$doNya['code'];
        }
        $data = ['result' => 1,
                 'data' => $delivery_orders
                ];
        return response()->json($data, 200);
    }

    public function deleteApi(Request $request)
    {
        $messages = array(
            'id.required' => 'There\'s an error with the data.',
            'id.exists' => 'There\'s an error with the data.'
        );

        $validator = \Validator::make($request->all(), [
            'id' => ['required', 'exists:orders,id,active,1']
        ], $messages);

        if ($validator->fails()){
            $data = ['result' => 0,
                     'data' => $validator->errors()
                    ];
            return response()->json($data, 401);
        }
        else{
            $order = DeliveryOrder::find($request->id);
            $order->active = false;
            $order->save();

            $data = ['result' => 1,
                     'data' => $order
                    ];
            return response()->json($data, 200);
        }
    }
}
