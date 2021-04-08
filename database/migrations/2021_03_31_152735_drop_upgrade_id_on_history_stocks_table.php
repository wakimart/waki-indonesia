<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUpgradeIdOnHistoryStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history_stocks', function (Blueprint $table) {
            $table->dropForeign(['updgrade_id']);
            $table->dropColumn('updgrade_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history_stocks', function (Blueprint $table) {
            $table->integer('updgrade_id')->unsigned();
            $table->foreign('updgrade_id')->references('id')->on('upgrades');
        });
    }
}
