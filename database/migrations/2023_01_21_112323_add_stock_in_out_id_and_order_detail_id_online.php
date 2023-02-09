<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStockInOutIdAndOrderDetailIdOnline extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->unsignedInteger("stock_id")->nullable();
            $table->foreign("stock_id")->references("id")->on("stock_in_outs");
            $table->unsignedInteger("order_detail_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropForeign('order_details_stock_id_foreign');
            $table->dropColumn(['stock_id', 'order_detail_id']);
        });
    }
}
