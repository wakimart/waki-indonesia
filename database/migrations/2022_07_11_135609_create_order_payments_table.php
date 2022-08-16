<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("order_id");
            $table->foreign("order_id")->references("id")->on("orders");
            $table->decimal('total_payment', 20, 2);
            $table->date("payment_date");
            $table->unsignedInteger("bank_id");
            $table->foreign("bank_id")->references("id")->on("banks");
            $table->integer("cicilan");
            $table->longText("image");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_payments');
    }
}
