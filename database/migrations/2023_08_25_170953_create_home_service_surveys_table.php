<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeServiceSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_service_surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('home_service_id')->unsigned();
            $table->foreign('home_service_id')->references('id')->on('home_services');
            $table->text('result');
            $table->string('online_signature');
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
        Schema::dropIfExists('home_service_surveys');
    }
}
