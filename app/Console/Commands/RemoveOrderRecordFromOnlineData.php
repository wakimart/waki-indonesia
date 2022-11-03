<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;
use App\OrderDetail;
use App\OrderPayment;
use App\HistoryUpdate;

class RemoveOrderRecordFromOnlineData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:order-record-from-online-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To remove orders where status new and reject and relation datas that is order_payments, order_details and history_updates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orderID = Order::whereIn('status', ['new', 'reject'])->pluck('id')->toArray();
        
        // history update
        $deleteHistoryUpdate = HistoryUpdate::where('type_menu', 'order')->whereIn('menu_id', $orderID)->delete();

        // order detail
        $deleteOrderDetail = OrderDetail::whereIn('order_id', $orderID)->delete();
        
        // order payment
        $deleteOrderPayment = OrderPayment::whereIn('order_id', $orderID)->delete();

        // order
        $deleteOrder = Order::whereIn('id', $orderID)->delete();
       
        echo "\noperation completed.\n";
    }
}
