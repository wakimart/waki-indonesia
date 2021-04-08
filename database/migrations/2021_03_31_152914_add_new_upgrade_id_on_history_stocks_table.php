<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewUpgradeIdOnHistoryStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history_stocks', function (Blueprint $table) {
            $table->integer('upgrade_id')->unsigned()->nullable();
            $table->foreign('upgrade_id')->references('id')->on('upgrades');
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
            $table->dropForeign(['upgrade_id']);
            $table->dropColumn('upgrade_id');
        });
    }
}
