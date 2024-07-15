<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockInOutConnectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_in_out_connects', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("stock_out_id")->nullable();
            $table->foreign("stock_out_id")->references("id")->on("stock_in_outs");
            $table->unsignedInteger("stock_in_id")->nullable();
            $table->foreign("stock_in_id")->references("id")->on("stock_in_outs");
            $table->enum('status', ['outstanding', 'confirm', 'cancel'])->default('outstanding');
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
        Schema::dropIfExists('stock_in_out_connects');
    }
}
