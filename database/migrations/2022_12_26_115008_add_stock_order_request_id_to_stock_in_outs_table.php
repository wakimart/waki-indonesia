<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStockOrderRequestIdToStockInOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_in_outs', function (Blueprint $table) {
            $table->unsignedInteger("stock_order_request_id")->nullable();
            $table->foreign("stock_order_request_id")->references("id")->on("stock_order_requests");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_in_outs', function (Blueprint $table) {
            $table->dropForeign('stock_in_outs_stock_order_request_id_foreign');
            $table->dropColumn('stock_order_request_id');
        });
    }
}
