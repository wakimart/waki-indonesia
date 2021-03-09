<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAcceptancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acceptances', function (Blueprint $table) {
            $table->string('no_mpc')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->dateTime('upgrade_date', 0);
            $table->integer('newproduct_id')->unsigned();
            $table->foreign('newproduct_id')->references('id')->on('products');
            $table->integer('oldproduct_id')->unsigned();
            $table->foreign('oldproduct_id')->references('id')->on('products');
            $table->dateTime('purchase_date', 0);
            $table->string('image')->nullable();
            $table->string('arr_condition')->nullable();
            $table->decimal('request_price', 20, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acceptances', function (Blueprint $table) {
            //
        });
    }
}
