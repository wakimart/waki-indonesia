<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWarehuseIdToStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn("other_product");
            $table->unsignedInteger("warehouse_id")->after("id");
            $table->foreign("warehouse_id")
                ->references("id")
                ->on("warehouses");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropForeign(["warehouse_id"]);
            $table->dropColumn(["warehouse_id"]);
            $table->string("other_product");
        });
    }
}
