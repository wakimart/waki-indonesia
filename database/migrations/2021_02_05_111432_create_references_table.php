<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('references', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('age')->nullable();
            $table->string('phone')->nullable();
            $table->integer('province')->nullable();
            $table->integer('city')->nullable();
            $table->integer('deliveryorder_id')->unsigned();
            $table->foreign('deliveryorder_id')->references('id')->on('delivery_orders');
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
        Schema::dropIfExists('references');
    }
}
