<?php

use App\Bank;
use App\OrderDetail;
use App\OrderPayment;
use App\Promo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MigrateOrdersToOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::beginTransaction();
        try {
            $orders = DB::table('orders')->get();
            foreach ($orders as $order) {
                $orderProducts = json_decode($order->product, true) ?? [];
                foreach ($orderProducts as $orderProduct) {
                    $orderDetail = new OrderDetail();
                    if (is_numeric($orderProduct['id']) && Promo::find($orderProduct['id'])) {
                        $orderDetail->promo_id = $orderProduct['id'];
                    } else {
                        $orderDetail->other = $orderProduct['id'];
                    }
                    $orderDetail->order_id = $order->id;
                    $orderDetail->qty = $orderProduct['qty'];
                    $orderDetail->type = OrderDetail::$Type['1'];
                    $orderDetail->save();
                }
                $orderUpgrade = json_decode($order->old_product, true) ?? null;
                if ($orderUpgrade) {
                    $orderDetail = new OrderDetail;
                    $orderDetail->order_id = $order->id;
                    $orderDetail->other = $orderUpgrade['name'];
                    $orderDetail->qty = $orderUpgrade['qty'];
                    $orderDetail->type = OrderDetail::$Type['3'];
                    $orderDetail->save();
                }
                $orderPrize = json_decode($order->prize, true) ??  null;
                if ($orderPrize) {
                    if (!in_array($orderPrize['name'], ["-", "0"]) 
                        || !is_numeric($orderPrize['name'])
                        || strcasecmp($orderPrize['name'], "Tidak ada") != 0) {
                        $orderDetail = new OrderDetail;
                        $orderDetail->order_id = $order->id;
                        $orderDetail->other = $orderPrize['name'];
                        $orderDetail->qty = $orderPrize['qty'];
                        $orderDetail->type = OrderDetail::$Type['2'];    
                        $orderDetail->save();
                    }
                }
                $orderBanks = json_decode($order->bank, true) ?? [];
                foreach ($orderBanks as $keyBank => $orderBank) {
                    $orderBank['id'] = Bank::find($orderBank['id']) ? $orderBank['id'] : 17;
                    $orderPayment = new OrderPayment();
                    $orderPayment->order_id = $order->id;
                    $orderPayment->total_payment = (($keyBank == 0) ? (is_numeric($order->down_payment) ? $order->down_payment : $order->total_payment) : 0);
                    $orderPayment->payment_date = $order->orderDate ?? date("Y-m-d", strtotime($order->created_at));
                    $orderPayment->bank_id = $orderBank['id'];
                    $orderPayment->cicilan = $orderBank['cicilan'];
                    $orderPayment->image = ($keyBank == 0 && $order->image) ? $order->image : json_encode([]);
                    $orderPayment->save();
                }
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            echo "Error! : " . $ex->getMessage()."\n"; 
            die;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
