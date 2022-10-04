<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Order;
use App\OrderDetail;
use App\OrderPayment;
use App\HistoryUpdate;
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
            
            $orderPaymentImage = json_decode($request->image, true);
            foreach($orderPaymentImage as $index => $image){
                $path = "sources/order";
                $file = $request['order_payment_file_'.$index];
                $fileName = $file['postname'];

                //compressed img
                $compres = Image::make($file['name']);
                $compres->resize(540, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path.'/'.$fileName);
            }
            
            $orderPayment->image = $request->image;
            $orderPayment->status = $request->status;
            $orderPayment->bank_account_id = $request->bank_account_id;            
            $orderPayment->type = $request->type;            
            $orderPayment->type_payment = $request->type_payment;            
            $orderPayment->credit_card_id = $request->credit_card_id;            
            $orderPayment->charge_percentage_bank = $request->charge_percentage_bank;            
            $orderPayment->charge_percentage_company = $request->charge_percentage_company;            
            $orderPayment->estimate_transfer_date = $request->estimate_transfer_date;            
            $orderPayment->save();

            $orderCode->updateDownPayment();
            $orderCode->save();

            $historyUpdate['type_menu'] = "Order";
            $historyUpdate['method'] = "Update";
            $historyUpdate['meta'] = json_encode([
                'user' => $request->user_id,
                'createdAt' => date("Y-m-d h:i:s"),
                'dataChange'=> ["Update Status Order Payment: " => [$orderPayment->id => $orderPayment->status]]
            ]);

            $historyUpdate['user_id'] = $request->user_id;
            $historyUpdate['menu_id'] = $orderPayment->order_id;
            $createData = HistoryUpdate::create($historyUpdate);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'data payment successfully copied'
            ]);
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage() 
            ]);
        }
    }
}