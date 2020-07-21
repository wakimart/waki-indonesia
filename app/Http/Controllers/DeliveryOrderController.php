<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\DeliveryOrder;
use App\Branch;
use App\Cso;
use App\HistoryUpdate;
use Illuminate\Validation\Rule;
use Validator;

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

    public function admin_ListDeliveryOrder(Request $request){
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
        return view('admin.list_deliveryorder', compact('deliveryOrders', 'countDeliveryOrders', 'branches'));
    }

    public function admin_DetailDeliveryOrder(Request $request){
        $deliveryOrder = DeliveryOrder::where('code', $request['code'])->first();
        $historyUpdateDeliveryOrder = HistoryUpdate::where('type_menu', 'Delivery Order')->where('menu_id', $deliveryOrder->id)->first();
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
        $historyUpdate['meta'] = ['user'=>$user['id'],'createdAt' => date("Y-m-d h:i:s"), 'dateChange'=> $deliveryOrders];
        $historyUpdate['user_id'] = $user['id'];


        $createData = HistoryUpdate::create($historyUpdate);


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
}
