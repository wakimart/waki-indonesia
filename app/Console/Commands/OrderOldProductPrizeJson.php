<?php

namespace App\Console\Commands;

use App\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OrderOldProductPrizeJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:oldproductprizejson';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Order old_product and prize to json';

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
        $orders = Order::whereNotNull('old_product')
            ->orWhereNotNull('prize')->get();
            DB::beginTransaction();
        try {
            $countUpdate = 0;
            foreach ($orders as $order) {
                $checkOldProduct = false;
                $checkPrize = false;
                if ($order['old_product'] != null && !$this->isJson($order['old_product'])) {
                    $order['old_product'] = json_encode([
                        "name" => $order['old_product'],
                        "qty" => 1
                    ]);
                    $checkOldProduct = true;
                }
    
                if ($order['prize'] != null && !$this->isJson($order['prize'])) {
                    $order['prize'] = json_encode([
                        "name" => $order['prize'],
                        "qty" => 1
                    ]);
                    $checkPrize = true;
                }

                if ($checkOldProduct || $checkPrize) {
                    $order->save();
                    $countUpdate++;
                }
            }
            DB::commit();
            echo "Update Successfully with " . $countUpdate . " order data";
        } catch (\Exception $ex) {
            DB::rollBack();
            echo "\nError: " . $ex->getMessage();
        }
    }

    function isJson($string) 
    {
        if(is_numeric($string)) return false;
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE; 
    }    
}
