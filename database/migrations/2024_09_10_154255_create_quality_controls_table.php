<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQualityControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quality_controls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->json('condition')->nullable();
            $table->json('accessories')->nullable();
            $table->json('evidence')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::table('stock_in_out_products', function (Blueprint $table) {
            $table->integer('quality_control_id')->unsigned()->nullable();
            $table->foreign('quality_control_id')->references('id')->on('quality_controls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_in_out_products', function (Blueprint $table) {
            $table->dropForeign(['quality_control_id']);
            $table->dropColumn('quality_control_id');
        });

        Schema::dropIfExists('quality_controls');
    }
}
