<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcceptanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('acceptance', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('code');
        //     $table->string('no_do')->nullable();;
        //     $table->string('name');
        //     $table->enum('status', ['new', 'rejected', 'approved']);
        //     $table->text('description');
        //     $table->boolean('active')->default(true);
        //     $table->integer('branch_id')->unsigned();
        //     $table->foreign('branch_id')->references('id')->on('branches');
        //     $table->integer('cso_id')->unsigned();
        //     $table->foreign('cso_id')->references('id')->on('csos');
        //     $table->unsignedInteger('user_id');
        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        //     $table->integer('order_id')->unsigned()->nullable();
        //     $table->foreign('order_id')->references('id')->on('orders');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acceptance');
    }
}
