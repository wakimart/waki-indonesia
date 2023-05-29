<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderCsoCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_cso_commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_commission_id');
            $table->foreign('order_commission_id')->references('id')->on('order_commissions');
            $table->unsignedInteger('cso_commission_id');
            $table->foreign('cso_commission_id')->references('id')->on('cso_commissions');
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
        Schema::dropIfExists('order_cso_commissions');
    }
}
