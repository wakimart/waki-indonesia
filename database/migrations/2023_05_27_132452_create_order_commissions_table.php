<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->unsignedInteger('cso_id');
            $table->foreign('cso_id')->references('id')->on('csos');
            $table->decimal('bonus', 20, 2)->default(0);
            $table->decimal('upgrade', 20, 2)->default(0);
            $table->decimal('smgt_nominal', 20, 2)->default(0);
            $table->decimal('excess_price', 20, 2)->default(0);
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
        Schema::dropIfExists('order_commissions');
    }
}
