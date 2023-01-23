<?php

namespace App\Http\Controllers\Api;

use App\HistoryUpdate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\OrderDetail;
use App\OrderPayment;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OnlineSideController extends Controller
{
    /**
     * check network to waki indonesia in offline network (office).
     *
     * @return \Illuminate\Http\Response
     */
    public function checkNetwork()
    {
        $ch = curl_init(env('OFFLINE_URL').'/api/end-point-for-check-status-network');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("api-key:".env('API_KEY')));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * send order data (orders and order details) to waki indonesia offline
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function sendOrderData($id)
    {
        $data = Order::find($id);
        $data['order_details'] = OrderDetail::where('order_id', $id)->get();
        $ch = curl_init(env('OFFLINE_URL').'/api/replicate-order-data');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    /**
     * send order payments data to waki indonesia offline (when acc)
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function sendOrderPaymentData($id)
    {
        $user = Auth::user();
        $data = OrderPayment::find($id);
        $data['order_code'] = $data->order->code;
        $data['user_id'] = $user['id'];
        $orderPaymentImage = json_decode($data->image, true);
        foreach($orderPaymentImage as $index => $image){
            $file = new \CURLFile(base64_encode(file_get_contents(public_path('sources/order/' . $image))), mime_content_type(public_path('sources/order/' . $image)), basename(public_path('sources/order/' . $image)));
            $data['order_payment_file_'.$index] = $file;
        }
        $ch = curl_init(env('OFFLINE_URL').'/api/replicate-order-payment-data');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    public function updateOrderStatus(Request $request)
    {
        DB::beginTransaction();
        try{
            if($request->header('api-key') == env('API_KEY')) {
                $order = Order::where('code', $request->code)->first();
                $dataBefore = Order::where('code', $request->code)->first();
                if(isset($order)){
                    if($request->has('order_details')){
                        $orderDetails = json_decode($request->order_details);
                        foreach ($orderDetails as $orderDetailNya) {
                            $orderDetail = OrderDetail::find($orderDetailNya->order_detail_id);
                            $orderDetail->offline_stock_id = $orderDetailNya->stock_id;
                            $orderDetail->save();
                        }
                    }

                    $order->status = $request->status;
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
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'data order not found'
                    ]);
                }
            } else {
                return response()->json([
                    "status" => "unauthenticated",
                    "message" => "you don't have access"
                ], 401);
            }
        }catch(\Exception $ex){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ]);
        }
    }
}