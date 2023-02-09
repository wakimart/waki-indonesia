<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderIdToStockInOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_in_outs', function (Blueprint $table) {
            $table->unsignedInteger("order_id")->nullable();
            $table->foreign("order_id")->references("id")->on("orders");
        });
        Schema::table('stock_in_out_products', function (Blueprint $table) {
            $table->unsignedInteger("order_detail_id")->nullable();
            $table->foreign("order_detail_id")->references("id")->on("order_details");
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
            $table->dropForeign('stock_in_outs_order_id_foreign');
            $table->dropColumn('order_id');
        });
        Schema::table('stock_in_out_products', function (Blueprint $table) {
            $table->dropForeign('stock_in_out_products_order_detail_id_foreign');
            $table->dropColumn('order_detail_id');
        });
    }
}
