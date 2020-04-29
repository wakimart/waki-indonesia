<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeliveryOrder;
use App\Order;
use App\Branch;
use App\Cso;

class OrderController extends Controller
{
    //Buat menampilkan form Order
    public function index()
    {
    	$promos = DeliveryOrder::$Promo;
    	$branches = Branch::all();
    	$csos = Cso::all();
    	$cashUpgrades = Order::$CashUpgrade;
    	$paymentTypes = Order::$PaymentType;
    	$banks = Order::$Banks;
        return view('order', compact('promos', 'branches', 'csos', 'cashUpgrades', 'paymentTypes', 'banks'));
    }

    public function store(Request $request){
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
    		if($arrKey[0] == 'product'){
    			if(isset($data['qty_'.$arrKey[1]])){
    				$data['arr_product'][$index] = [];
    				$data['arr_product'][$index]['id'] = $value;
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

    	return redirect()->route('order_success', ['code'=>$order['code']]);
    }

    public function successorder(Request $request){
    	$order = Order::where('code', $request['code'])->first();
        return view('order_success', compact('order'));
    }

    public function admin_AddOrder(){
        $promos = DeliveryOrder::$Promo;
        $branches = Branch::all();
        $csos = Cso::all();
        $cashUpgrades = Order::$CashUpgrade;
        $paymentTypes = Order::$PaymentType;
        $banks = Order::$Banks;
        return view('admin.add_order', compact('promos', 'branches', 'csos', 'cashUpgrades', 'paymentTypes', 'banks'));
    }

    public function admin_StoreOrder(Request $request){
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
            if($arrKey[0] == 'product'){
                if(isset($data['qty_'.$arrKey[1]])){
                    $data['arr_product'][$index] = [];
                    $data['arr_product'][$index]['id'] = $value;
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

        return response()->json(['success' => 'Berhasil']);
    }

    public function admin_ListOrder(){
        $orders = Order::all();
        return view('admin.list_order', compact('orders'));
    }

    public function admin_DetailOrder(Request $request){
        $order = Order::where('code', $request['code'])->first();
        return view('admin.detail_order', compact('order'));
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
            $promos = DeliveryOrder::$Promo;
            $branches = Branch::all();
            $csos = Cso::all();
            $cashUpgrades = Order::$CashUpgrade;
            $paymentTypes = Order::$PaymentType;
            $banks = Order::$Banks;
            return view('admin.update_order', compact('orders','promos', 'branches', 'csos', 'cashUpgrades', 'paymentTypes', 'banks'));
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
        $data = $request->all();
        $orders = Order::find($request->input('idOrder'));
        $orders['no_member'] = $request->input('no_member');
        $orders['name'] = $request->input('name');
        $orders['address'] = $request->input('address');
        $orders['phone'] = $request->input('phone');
        $orders['cash_upgrade'] = $request->input('cash_upgrade');
        $orders['total_payment'] = $request->input('total_payment');
        $orders['down_payment'] = $request->input('down_payment');
        $orders['remaining_payment'] = $request->input('remaining_payment');
        
        //pembentukan array product
        $index = 0;
        $data['arr_product'] = [];
        foreach ($data as $key => $value) {
            $arrKey = explode("_", $key);
            if($arrKey[0] == 'product'){
                if(isset($data['qty_'.$arrKey[1]])){
                    $data['arr_product'][$index] = [];
                    $data['arr_product'][$index]['id'] = $value;
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

        $orders['old_product'] = $request->input('old_product');
        $orders['prize'] = $request->input('prize');
        $orders['cso_id'] = $request->input('idCSO');
        $orders['30_cso_id'] = $request->input('idCSO30');
        $orders['70_cso_id'] = $request->input('idCSO70');
        $orders['branch_id'] = $request->input('branch_id');
        $orders['city'] = $request->input('city');
        $orders['customer_type'] = $request->input('customer_type');
        $orders['description'] = $request->input('description');
        $orders->save();

        return response()->json(['success' => 'Berhasil!']);
        
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