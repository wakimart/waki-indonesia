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

class DeliveryOrderController extends Controller
{
	//Buat menampilkan form DO
    public function index()
    {
    	$promos = DeliveryOrder::$Promo;
    	$branches = Branch::all();
    	$csos = Cso::all();
        return view('deliveryorder', compact('promos', 'branches', 'csos'));
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

    	return redirect()->route('successorder', ['code'=>$deliveryOrder['code']]);
    }

    public function successorder(Request $request){
    	$deliveryOrder = DeliveryOrder::where('code', $request['code'])->first();
        return view('ordersuccess', compact('deliveryOrder'));
    }

    public function fetchCso(Request $request){
    	$csos = Cso::where('code', $request->txt)->get();
    	$result = 'false';
    	if(sizeof($csos) > 0){
    		$result = 'true';
    	}
    	return $result;
    }

    public function listDeliveryOrder(Request $request){
        $deliveryOrders = DeliveryOrder::all();        
        return view('templistregwaki1995', compact('deliveryOrders'));
    }

    public function admin_AddDeliveryOrder(){
        $promos = DeliveryOrder::$Promo;
        $branches = Branch::all();
        $csos = Cso::all();
        return view('admin.add_deliveryorder', compact('promos', 'branches', 'csos'));
    }

    public function admin_StoreDeliveryOrder(Request $request){
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
                    $data['arr_product'][$key]['qty'] = $data['qty_'.$arrKey[1]];
                }
            }
        }
        $data['arr_product'] = json_encode($data['arr_product']);

        $deliveryOrder = DeliveryOrder::create($data);

        return response()->json(['success' => 'Berhasil!!']);
    }

    public function admin_ListDeliveryOrder(){
        //khususu head-manager, head-admin, admin
        $deliveryOrders = DeliveryOrder::all();

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
        
        return view('admin.list_deliveryorder', compact('deliveryOrders'));
    }

    public function admin_DetailDeliveryOrder(Request $request){
        $deliveryOrder = DeliveryOrder::where('code', $request['code'])->first();
        return view('admin.detail_deliveryorder', compact('deliveryOrder'));
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

        return response()->json(['success' => 'Berhasil!!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request) {
        
    }

    //KHUSUS API APPS
    public function fetchPromosApi(){
        $data = DeliveryOrder::$Promo;
        $promos = [];
        foreach ($data as $key => $value) {
            $value['id'] = $key;
            array_push($promos, $value);
        }
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
                                ->select('delivery_orders.id', 'delivery_orders.code', 'delivery_orders.created_at', 'delivery_orders.name as customer_name', 'delivery_orders.arr_product', 'branches.code as branch_code', 'branches.name as branch_name', 'csos.code as cso_code', 'csos.name as cso_name')
                                ->paginate($request->limit);

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
