<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcceptanceStatusLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acceptance_status_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('acceptance_id')->unsigned();
            $table->foreign('acceptance_id')->references('id')->on('acceptance');
            $table->enum('status', ['new', 'rejected', 'approved']);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('acceptance_status_log');
    }
}
