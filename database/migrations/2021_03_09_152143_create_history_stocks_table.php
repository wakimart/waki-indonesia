<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('updgrade_id')->unsigned();
            $table->foreign('updgrade_id')->references('id')->on('upgrades');
            $table->integer('stock_id')->unsigned();
            $table->foreign('stock_id')->references('id')->on('stocks');
            $table->enum('type_warehouse', ['Display', 'Ready'])->nullable();
            $table->integer('quantity')->nullable();
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
        Schema::dropIfExists('history_stocks');
    }
}
