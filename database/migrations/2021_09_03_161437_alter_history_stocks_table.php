<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterHistoryStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("history_stocks", function (Blueprint $table) {
            $table->dropForeign(["upgrade_id "]);
            $table->dropColumn(["type_warehouse", "upgrade_id"]);
            $table->enum("type", ["in", "out"])->nullable()->after("stock_id");
            $table->date("date")->useCurrent()->nullable()->after("stock_id");
            $table->string("code")->nullable()->after("stock_id");
            $table->string("description", 300)->nullable()->after("quantity");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("history_stocks", function (Blueprint $table) {
            $table->dropColumn(["code", "date", "type", "description"]);
            $table->enum("type_warehouse", ["Display", "Ready"])->after("stock_id");
            $table->unsignedInteger("upgrade_id")->after("stock_id");
            $table->foreign("upgrade_id")->references("id")->on("upgrades");
        });
    }
}
