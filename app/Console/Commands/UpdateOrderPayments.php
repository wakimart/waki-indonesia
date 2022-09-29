<?php

namespace App\Console\Commands;

use App\OrderPayment;
use Illuminate\Console\Command;

class UpdateOrderPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:order-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'for update payment type in new bank account system';

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
        $orderPayments = OrderPayment::all();
        $bar = $this->output->createProgressBar(count($orderPayments));
        $bar->start();

        foreach($orderPayments as $op){
            $updateOP = OrderPayment::find($op->id);
            $updateOP->type_payment = $updateOP->order->payment_type == 1 ? 'cash' : 'card';
            $updateOP->update();
            $bar->advance();
        }
        $bar->finish();
        echo "\noperation completed.\n";
    }
}
