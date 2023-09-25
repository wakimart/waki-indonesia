<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTheraphySignInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theraphy_sign_ins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('theraphy_service_id')->unsigned();
            $table->foreign('theraphy_service_id')->references('id')->on('theraphy_services');
            $table->date('therapy_date')->useCurrent();
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
        Schema::dropIfExists('theraphy_sign_ins');
    }
}
