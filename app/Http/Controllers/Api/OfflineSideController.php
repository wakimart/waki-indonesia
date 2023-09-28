<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Order;
use App\OrderDetail;
use App\OrderPayment;
use App\HistoryUpdate;
use App\Http\Controllers\StockOrderRequestController;
use App\User;
use App\Cso;
use App\Branch;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;

class OfflineSideController extends Controller
{
    /**
     * network end point for waki indonesia online side.
     *
     * @return \Illuminate\Http\Response
     */
    public function networkEndPoint(Request $request)
    {
        $apiKey = $request->header('api-key');
        if($apiKey == env('API_KEY')){
            return response()->json([
                "status" => "success",
                "message" => "network available"
            ], 200);
        }else{
            return response()->json([
                "status" => "unauthenticated",
                "message" => "you don't have access"
            ], 401);
        }
        return false;
    }

    /**
     * copy data (orders and order_details) from waki indonesia online to offline
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function replicateOrderData(Request $request)
    {
        DB::beginTransaction();
        try{
            if($request->status == 'process'){
                $order = new Order();
                $order->code = $request->code;
                $order->no_member = $request->no_member;
                $order->name = $request->name;
                $order->address = $request->address;
                $order->cash_upgrade = $request->cash_upgrade;
                $order->total_payment = $request->total_payment;
                $order->down_payment = $request->down_payment;
                $order->remaining_payment = $request->remaining_payment;
                $order->cso_id = $request->cso_id;
                $order->branch_id = $request->branch_id;
                $order['30_cso_id'] = $request->input('30_cso_id');
                $order['70_cso_id'] = $request->input('70_cso_id');
                $order->customer_type = $request->customer_type;
                $order->description = $request->description;
                $order->phone = $request->phone;
                $order->orderDate = $request->orderDate;
                $order->know_from = $request->know_from;
                $order->province = $request->province;
                $order->city = $request->city;
                $order->distric = $request->distric;
                $order->status = $request->status;
                $order->delivery_cso_id = $request->delivery_cso_id;
                $order->temp_no = $request->temp_no;
                $order->save();

                foreach($request->order_details as $detail){
                    $dataDetail = new OrderDetail();
                    $dataDetail->order_id = $order->id;
                    $dataDetail->product_id = $detail['product_id'];
                    $dataDetail->promo_id = $detail['promo_id'];
                    $dataDetail->qty = $detail['qty'];
                    $dataDetail->type = $detail['type'];
                    $dataDetail->other = $detail['other'];
                    $dataDetail->order_detail_id = $detail['order_detail_id'];
                    $dataDetail->save();
                }

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'data successfully copied'
                ]);
            }else{
                $order = Order::where('code', $request->code)->first();
                $dataBefore = Order::where('code', $request->code)->first();
                if(isset($order)){
                    $order->status = $request->status;
                    if ($order->status == Order::$status['6']) {
                        StockOrderRequestController::inserStockOrderRequest($order->id, $request->prodIdNya, $request->prodQty);
                    } if($order->status == Order::$status['3']){
                        $order->delivery_cso_id = json_encode($request->delivery_cso_id);
                    } else if ($order->status == Order::$status['4']) {
                        if(isset($request->delivered_image) && count($request->delivered_image) > 0){
                            foreach($request->delivered_image as $index => $image){
                                $path = $request['delivered_image_file_' . $index];
                                $filename = basename($path);
                
                                Image::make($path)->save('/var/www/public_html/waki-indonesia/sources/order/' . $filename);
                            }
                            $order->delivered_image = json_encode($request->delivered_image);
                        }
                    }
                    $order->update();
                    
                    $user = User::where('code', $request->user_id)->first();
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
                    return response()->json([
                        'status' => 'success',
                        'message' => 'data successfully updated'
                    ]);
                }else{
                    return response()->json([
                        'status' => 'error',
                        'message' => 'data order not found'
                    ]);
                }
            }            
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ]);
        }
    }

    /**
     * copy data (order payments) from waki indonesia online to offline (when acc)
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function replicateOrderPaymentData(Request $request)
    {
        DB::beginTransaction();
        try{
            $orderPayment = new OrderPayment();
            $orderCode = Order::where('code', $request->order_code)->first();
            $orderPayment->order_id = $orderCode->id;
            $orderPayment->total_payment = $request->total_payment;
            $orderPayment->payment_date = $request->payment_date;
            $orderPayment->bank_id = $request->bank_id;
            $orderPayment->cicilan = $request->cicilan;
            
            foreach($request->image as $index => $image){
                $path = $request['order_payment_file_' . $index];
                $filename = basename($path);

                Image::make($path)->save('/var/www/public_html/waki-indonesia/sources/order/' . $filename);
            }
            
            $orderPayment->image = json_encode($request->image);
            $orderPayment->status = "unverified";
            $orderPayment->save();

            $orderCode->updateDownPayment();
            $orderCode->save();

            $user = User::where('code', $request->user_id)->first();
            $historyUpdate['type_menu'] = "Order";
            $historyUpdate['method'] = "Update";
            $historyUpdate['meta'] = json_encode([
                'user' => $user->id,
                'createdAt' => date("Y-m-d h:i:s"),
                'dataChange'=> ["Update Status Order Payment: " => [$orderPayment->id => $orderPayment->status]]
            ]);

            $historyUpdate['user_id'] = $user->id;
            $historyUpdate['menu_id'] = $orderPayment->order_id;
            $createData = HistoryUpdate::create($historyUpdate);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'data payment successfully copied'
            ], 200);
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    public function sendUpdateOrderStatus($code, $status, $user_id, $order_details)
    {
        $data = [
            'code' => $code,
            'status' => $status,
            'user_id' => $user_id,
            'order_details' => $order_details,
        ];
        $ch = curl_init(env('ONLINE_URL').'/api/update-order-status');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', "api-key:".env('API_KEY')));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch) . " curl_errno: " . curl_errno($ch) . " response: " . $response;
        }
        curl_close($ch);
        $response = json_decode($response, true);
        if (isset($response['status']) && $response['status'] == 'success') {
            return $response;
        } else {
            throw new \Exception($error_msg ?? $response['message'] ?? $response ?? 'Network Error!');
        }
    }


    /**
     * update order data from online to offline (total payment, products, etc)
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function updateOrderData(Request $request)
    {
        // return response()->json($request->all());
        DB::beginTransaction();
        try{
            $historyUpdate= [];
            $data = $request->all();
            $orders = Order::where('code', $data['order_code'])->first();
            $dataBefore = Order::where('code', $data['order_code'])->first();
            $orders['cso_id'] = Cso::where('code', $data['cso_id'])->first()['id'];
            $orders['30_cso_id'] = Cso::where('code', $data['30_cso_id'])->first()['id'];
            $orders['70_cso_id'] = Cso::where('code', $data['70_cso_id'])->first()['id'];
            $orders['no_member'] = $data['no_member'];
            $orders['name'] = $data['name'];
            $orders['address'] = $data['address'];
            $orders['cash_upgrade'] = $data['cash_upgrade'];
            $orders['total_payment'] = $data['total_payment'];
            $orders['remaining_payment'] = $data['remaining_payment'];
            $orders['customer_type'] = $data['customer_type'];
            $orders['description'] = $data['description'];
            $orders['phone'] = $data['phone'];
            $orders['know_from'] = $data['know_from'];
            $orders['province'] = $data['province_id'];
            $orders['city'] = $data['city'];
            $orders['distric'] = $data['distric'];

            $orderDetails = OrderDetail::where('order_id', $orders['id'])->get();
            $orderDetailOlds = OrderDetail::where('order_id', $orders['id'])->get();

            $orderPayments = OrderPayment::where("order_id", $orders["id"])->get();
            $orderPaymentOlds = OrderPayment::where("order_id", $orders["id"])->get();
            
            //pembentukan array product
            $index = 0;
            foreach ($data as $key => $value) {
                $arrKey = explode("_", $key);
                if ($arrKey[0] == 'product') {
                    if (isset($arrKey[1]) && isset($data['qty_' . $arrKey[1]])) {
                        $isUpdateOrCreateProduct = true;
                        if (isset($data['orderdetailold'][$arrKey[1]])) {
                            $orderDetail = OrderDetail::where('order_detail_id', $data['orderdetailold'][$arrKey[1]])->first();
                            if($orderDetail){
                                $orderDetail->product_id = null;
                                $orderDetail->promo_id = null;
                                $orderDetail->other = null;
                            }else{
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'the order detail id on the offline side is still empty'
                                ], 500);
                            }
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
                if ($orderDetail['type'] == OrderDetail::$Type['1'] && !in_array($orderDetail['order_detail_id'], $data['orderdetailold'])) {
                    $orderDetail->delete();
                }
            }
            //pembentukan array old_product
            if (isset($data['old_product'])) {
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
            //pembentukan array takeaway
            if (isset($data['takeaway'])) {
                $orderDetail = $orderDetailOlds->filter(function ($item) {
                    return $item->type == OrderDetail::$Type['4'];
                })->first();
                if (!$orderDetail) {
                    $orderDetail = new OrderDetail;
                }
                $orderDetail->product_id = null;
                $orderDetail->other = null;
                $orderDetail->order_id = $orders['id'];
                $orderDetail->type = OrderDetail::$Type['4'];
                $orderDetail->qty = $data['takeaway_qty'] ?? 1;
                if ($data['takeaway'] == "other") {
                    $orderDetail->other = $data['takeaway_other'];
                } else {
                    $orderDetail->product_id = $data['takeaway'];
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
                        if (isset($orderPayments[$arrKey[1]]['id'])) {
                            $orderPayment = OrderPayment::find($orderPayments[$arrKey[1]]['id']);
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
                                    if (File::exists("/var/www/public_html/waki-indonesia/sources/order/" . $orderPaymentImages[$i])) {
                                        File::delete("/var/www/public_html/waki-indonesia/sources/order/" . $orderPaymentImages[$i]);
                                    }
                                    unset($orderPaymentImages[$i]);
                                }
                                if ($request->hasFile('images_' . $arrKey[1] . '_' . $i)) {
                                    $path = "sources/order";
    
                                    // Hapus Img Lama Jika Update Image
                                    if (isset($orderPaymentImages[$i])) {
                                        if (File::exists("/var/www/public_html/waki-indonesia/sources/order/" . $orderPaymentImages[$i])) {
                                            File::delete("/var/www/public_html/waki-indonesia/sources/order/" . $orderPaymentImages[$i]);
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
            // convert order payment id from online to offline
            $getIDFromOrderPaymentOlds = [];
            foreach($data['oldorderpaymentamount'] as $index => $amount){
                $orderPaymentOfflineSide = OrderPayment::where('order_id', $orders->id)->where('total_payment', $amount)->where('bank_id', $data['oldorderpaymentbankid'][$index])->first();
                array_push($getIDFromOrderPaymentOlds, $orderPaymentOfflineSide->id);
            }
            // Hapus Old Order Payment
            foreach ($orderPayments as $orderPayment) {
                if (!in_array($orderPayment['id'], $getIDFromOrderPaymentOlds)) {
                    $orderPaymentImages = json_decode($orderPayment->image, true);
                    foreach ($orderPaymentImages as $orderPaymentImage) {
                        if (File::exists("/var/www/public_html/waki-indonesia/sources/order/" . $orderPaymentImage)) {
                            File::delete("/var/www/public_html/waki-indonesia/sources/order/" . $orderPaymentImage);
                        }
                    }
                    $orderPayment->delete();
                }
            }
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

            $user = User::where('code', $request->user_code)->first();
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
            return response()->json([
                'status' => 'success',
                'message' => 'data order successfully updated'
            ], 200);
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    /**
     * replicate cso data from online side
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function replicateCSOData(Request $request)
    {
        DB::beginTransaction();
        try{
            $cso = new Cso();
            $cso->code = strtoupper($request->code);
            $cso->name = strtoupper($request->name);
            $branch = Branch::where('code', $request->branch_code)->first();
            if(!$branch){
                return response()->json([
                    'status' => 'error',
                    'message' => 'branch not found, please contact IT'
                ], 500);
            }
            $cso->branch_id = $branch->id;
            $cso->phone = $request->phone;
            $cso->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'data cso successfully copied'
            ], 200);
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    /**
     * update cso data from online by previous cso code (post)
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function updateCSOData(Request $request)
    {
        DB::beginTransaction();
        try{
            $cso = Cso::where('code', $request->previous_code)->first();
            if(!$cso){
                return response()->json([
                    'status' => 'error',
                    'message' => 'cso not found, please contact IT'
                ], 500);
            }
            $cso->code = strtoupper($request->code);
            $cso->name = strtoupper($request->name);
            if($request->branch_code){
                $branch = Branch::where('code', $request->branch_code)->first();    
                $cso->branch_id = $branch->id;            
            }
            $cso->phone = $request->phone;
            $cso->update();

            $user = User::where('code', $request->user_code)->first();
            $historyUpdate= [];
            $historyUpdate['type_menu'] = "Cso";
            $historyUpdate['method'] = "Update";
            $historyUpdate['meta'] = ['user'=>$user['id'],'createdAt' => date("Y-m-d h:i:s"), 'dateChange'=> $cso];
            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $cso->id;

            $createData = HistoryUpdate::create($historyUpdate);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'data cso successfully updated'
            ], 200);
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    /**
     * delete cso data from online by cso code (post)
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function deleteCSOData(Request $request)
    {
        DB::beginTransaction();
        try{
            $cso = Cso::where('code', $request->cso_code)->first();
            $cso->active = false;
            $cso->save();

            $user = User::where('code', $request->user_code)->first();
            $historyUpdate= [];
            $historyUpdate['type_menu'] = "Cso";
            $historyUpdate['method'] = "Delete";
            $historyUpdate['meta'] = json_encode(['user'=>$user['id'],'createdAt' => date("Y-m-d h:i:s"), 'dateChange'=> json_encode(array('Active'=>$cso->active))]);
            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $cso->id;

            $createData = HistoryUpdate::create($historyUpdate);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'data cso successfully deleted'
            ], 200);
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ], 500);
        }
    }
}