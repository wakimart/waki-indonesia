<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\OrderDetail;
use App\OrderPayment;
use DB;
use Illuminate\Support\Facades\Auth;

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
}