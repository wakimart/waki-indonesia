<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeliveryOrder;

class DeliveryOrderController extends Controller
{
	//Buat menampilkan form DO
    public function index()
    {
    	$promos = DeliveryOrder::$Promo;
        return view('deliveryorder', compact('promos'));
    }

    public function store(Request $request){
    	$data = $request->all();
    	$data['code'] = "DO_BOOK/".strtotime(date("Y-m-d H:i:s"))."/".substr($data['phone'], -4);

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

    	return redirect()->route('successorder', ['code'=>$deliveryOrder['code']]);
    }

    public function successorder(Request $request){
    	$deliveryOrder = DeliveryOrder::where('code', $request['code'])->first();
        return view('ordersuccess', compact('deliveryOrder'));
    }
}
