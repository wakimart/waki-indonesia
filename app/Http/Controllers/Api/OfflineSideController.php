<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Order;
use App\OrderDetail;
use App\OrderPayment;
use App\HistoryUpdate;
use App\User;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

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
                $order->save();

                foreach($request->order_details as $detail){
                    $dataDetail = new OrderDetail();
                    $dataDetail->order_id = $order->id;
                    $dataDetail->product_id = $detail['product_id'];
                    $dataDetail->promo_id = $detail['promo_id'];
                    $dataDetail->qty = $detail['qty'];
                    $dataDetail->type = $detail['type'];
                    $dataDetail->other = $detail['other'];
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
                    if($order->status == Order::$status['3']){
                        $order->delivery_cso_id = json_encode($request->delivery_cso_id);
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

                Image::make($path)->save('/var/www/public_html/waki-indonesia/public/sources/order/' . $filename);
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
}