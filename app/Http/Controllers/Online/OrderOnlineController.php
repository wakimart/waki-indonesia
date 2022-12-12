<?php

namespace App\Http\Controllers\Online;

use App\HistoryUpdate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderOnline;
use App\OrderPayment;
use App\OrderPaymentOnline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

class OrderOnlineController extends Controller
{
    public static function replicateOrderData($request, $orderOnline)
    {
        try {
            if($request->status_order == 'process'){
                $order = new Order();
                $order->id = $orderOnline->id;
                $order->code = $orderOnline->code;
                $order->no_member =  $orderOnline->no_member;
                $order->name = $orderOnline->name;
                $order->address = $orderOnline->address;
                $order->cash_upgrade = $orderOnline->cash_upgrade;
                $order->total_payment = $orderOnline->total_payment;
                $order->down_payment = $orderOnline->down_payment;
                $order->remaining_payment = $orderOnline->remaining_payment;
                $order->cso_id = $orderOnline->cso_id;
                $order->branch_id = $orderOnline->branch_id;
                $order['30_cso_id'] = $orderOnline['30_cso_id'];
                $order['70_cso_id'] = $orderOnline['70_cso_id'];
                $order->customer_type = $orderOnline->customer_type;
                $order->description = $orderOnline->description;
                $order->phone = $orderOnline->phone;
                $order->orderDate = $orderOnline->orderDate;
                $order->know_from = $orderOnline->know_from;
                $order->province = $orderOnline->province;
                $order->city = $orderOnline->city;
                $order->distric = $orderOnline->distric;
                $order->status = $orderOnline->status;
                $order->delivery_cso_id = $orderOnline->delivery_cso_id;
                $order->save();

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'data successfully copied'
                ]);
            } else {
                $order = Order::find($request->input('orderId'));
                if (isset($order)) {
                    $order->status = $orderOnline->status;
                    if ($order->status == Order::$status['3']) {
                        $order->delivery_cso_id = $orderOnline->delivery_cso_id;
                    }
                    $order->update();
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'data successfully updated'
                    ]);
                } else{
                    return response()->json([
                        'status' => 'error',
                        'message' => 'data order not found'
                    ]);
                }
            }
        } catch (\Exception $ex) {
            DB::rollback();
            throw new \Exception($ex->getMessage());
        }
    }

    public static function replicateOrderPaymentData($request, $orderPaymentOnline)
    {
        DB::beginTransaction();
        try{
            $order = Order::where('id', $orderPaymentOnline->order_id)->first();

            $orderPayment = new OrderPayment();
            $orderPayment->id = $orderPaymentOnline->id;
            $orderPayment->order_id = $orderPaymentOnline->order_id;
            $orderPayment->total_payment = $orderPaymentOnline->total_payment;
            $orderPayment->payment_date = $orderPaymentOnline->payment_date;
            $orderPayment->bank_id = $orderPaymentOnline->bank_id;
            $orderPayment->cicilan = $orderPaymentOnline->cicilan;
            
            foreach(json_decode($orderPaymentOnline->image, true) as $index => $image){
                $path = env('APP_URL_SERVER').'/sources/order/' . $image;
                $filename = basename($path);

                Image::make($path)->save(public_path('/sources/order/' . $filename));
            }
            
            $orderPayment->image = $orderPaymentOnline->image;
            $orderPayment->status = "unverified";
            $orderPayment->save();

            $order->updateDownPayment();
            $order->save();

            $user = Auth::user();
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
            throw new \Exception($ex->getMessage());
        }
    }

    // Update Status Order
    public function updateStatucOrder(Request $request)
    {
        DB::connection('server')->beginTransaction();
        try {
            $orderOnline = OrderOnline::find($request->input('orderId'));
            $dataBefore = OrderOnline::find($request->input('orderId'));
            $last_status_order = $orderOnline->status;
            $orderOnline->status = $request->input('status_order');
            
            // Save Delivery CSO
            if ($orderOnline->status == Order::$status['3']) {
                $orderOnline->delivery_cso_id = json_encode($request->delivery_cso_id);
            }        
            $orderOnline->save();

            // Copy Order Online to Offline
            self::replicateOrderData($request, $orderOnline);

            $user = Auth::user();
            $historyUpdate['type_menu'] = "Order";
            $historyUpdate['method'] = "Update Status";
            $historyUpdate['meta'] = json_encode([
                'user'=>$user['id'],
                'createdAt' => date("Y-m-d h:i:s"),
                'dataChange'=> array_diff(json_decode($orderOnline, true), json_decode($dataBefore,true))
            ]);

            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $orderOnline->id;
            $createData = HistoryUpdate::on('server')->create($historyUpdate);
            DB::connection('server')->commit();
            
            return redirect()->back()->with('success', 'Status Order Berhasil Di Ubah');
        } catch (\Exception $ex) {
            DB::connection('server')->rollback();
            return redirect()->back()->with('error', 'Status Order Gagal Di Ubah');
        }
    }

    // Update Status Order Payment Online
    public function updateStatusOrderPayment(Request $request)
    {
        if ($request->has('order_id') && $request->has('order_payment_id')) {
            $orderPaymentOnline = OrderPaymentOnline::where('order_id', $request->get('order_id'))
                ->where('id', $request->get('order_payment_id'))->first();
    
            if ($orderPaymentOnline) {
                DB::connection('server')->beginTransaction();
                try{
                    $data = $request->all();
                    $orderPaymentOnline_status = $request->get('status_acc');
                    if ($orderPaymentOnline_status =='true') {
                        $orderPaymentOnline->status = 'verified';
                    } else if($orderPaymentOnline_status == 'false') {
                        $orderPaymentOnline->status = 'rejected';
                    }
                    $orderPaymentOnline->save();

                    // Set Order Down Payment
                    $order = OrderOnline::find($data['order_id']);
                    $order->updateDownPayment();
                    $order->save();

                    // Copy Order Payment Online to Offline
                    if ($orderPaymentOnline_status == 'true') {
                        self::replicateOrderPaymentData($request, $orderPaymentOnline);
                    }

                    $user = Auth::user();
                    $historyUpdate['type_menu'] = "Order";
                    $historyUpdate['method'] = "Update";
                    $historyUpdate['meta'] = json_encode([
                        'user'=>$user['id'],
                        'createdAt' => date("Y-m-d h:i:s"),
                        'dataChange'=> ["Update Status Order Payment: " => [$orderPaymentOnline->id => $orderPaymentOnline->status ]]
                    ]);

                    $historyUpdate['user_id'] = $user['id'];
                    $historyUpdate['menu_id'] = $data['order_id'];
                    $createData = HistoryUpdate::on('server')->create($historyUpdate);
                    
                    DB::connection('server')->commit();
                    return redirect()->back()->with('success', 'Order Payment Berhasil Di Ubah');
                }catch(\Exception $ex){
                    DB::connection('server')->rollback();
                    return redirect()->back()->withErrors($ex->getMessage());
                }
            }
        }
        return response()->json(['result' => 'Gagal!!']);
    }
}
