<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\DeliveryOrder;
use App\Order;
use App\Branch;
use App\Cso;
use App\User;

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

        return response()->json(['success' => 'Berhasil']);
    }

    public function admin_ListOrder(Request $request){
        //khususu head-manager, head-admin, admin
        $orders = Order::where('active', true);

        //khusus akun CSO
        if(Auth::user()->roles[0]['slug'] == 'cso'){
            $orders = Order::where('cso_id', Auth::user()->cso['id'])->where('active', true);
        }

        //khusus akun branch dan area-manager
        if(Auth::user()->roles[0]['slug'] == 'branch' || Auth::user()->roles[0]['slug'] == 'area-manager'){
            $arrbranches = [];
            foreach (Auth::user()->listBranches() as $value) {
                array_push($arrbranches, $value['id']);
            }
            $orders = Order::WhereIn('branch_id', $arrbranches)->where('active', true);
        }

        //kalau ada Filter
        if($request->has('filter_branch') && Auth::user()->roles[0]['slug'] != 'branch'){
            $orders = $orders->where('branch_id', $request->filter_branch);
        }
        if($request->has('filter_cso') && Auth::user()->roles[0]['slug'] != 'cso'){
            $orders = $orders->where('cso_id', $request->filter_cso);
        }

        $orders = $orders->get();
        $branches = Branch::Where('active', true)->get();

        return view('admin.list_order', compact('orders', 'branches'));
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

    //KHUSUS API APPS
    public function fetchBanksApi(){
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

    public function addApi(Request $request)
    {
        $messages = array(
                'cso_id.required' => 'The CSO Code field is required.',
                'cso_id.exists' => 'Wrong CSO Code.',
                'branch_id.required' => 'The Branch must be selected.',
                'old_product.required_if' => 'The old product field is required when upgrade.'
            );

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'cash_upgrade' => 'required',
            'product_0' => 'required',
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
            $data['cso_id_30'] = Cso::where('code', $data['cso_id_30'])->first()['id'];
            $data['cso_id_70'] = Cso::where('code', $data['cso_id_70'])->first()['id'];

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
                                ->select('orders.id', 'orders.code', 'orders.created_at', 'orders.name as customer_name', 'orders.product', 'branches.code as branch_code', 'branches.name as branch_name', 'csos.code as cso_code', 'csos.name as cso_name')
                                ->paginate($request->limit);

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

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'cash_upgrade' => 'required',
            'product_0' => 'required',
            'qty_0' => 'required',
            'old_product' => 'required_if:cash_upgrade,==,2',
            'payment_type' => 'required',
            'bank_0' => 'required',
            'cicilan_0' => 'required',
            'total_payment' => 'required',
            'down_payment' => 'required',
            'remaining_payment' => 'required',
            'cso_id' => ['required', 'exists:csos,code'],
            '30_cso_id' => ['required', 'exists:csos,code'],
            '70_cso_id' => ['required', 'exists:csos,code'],
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
            $order = Order::find($data['id']);
            $order->fill($data)->save();

            $data = ['result' => 1,
                     'data' => $order
                    ];
            return response()->json($data, 200);
        }
    }


    public function viewApi($id)
    {
        //khususu head-manager, head-admin, admin
        $orders = Order::where('orders.active', true);

        $orders = $orders->leftjoin('branches', 'orders.branch_id', '=', 'branches.id')
                            ->leftjoin('csos', 'orders.cso_id', '=', 'csos.id')
                            ->select('orders.id', 'orders.code', 'orders.created_at', 'orders.name as customer_name', 'orders.product', 'branches.code as branch_code', 'branches.name as branch_name', 'csos.code as cso_code', 'csos.name as cso_name')
                            ->get();

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
        }
        $orders = $orders->find($id);
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
            $order = order::find($request->id);
            $order->active = false;
            $order->save();

            $data = ['result' => 1,
                     'data' => $order
                    ];
            return response()->json($data, 200);
        }
    }
}
