<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('no_member')->nullable();
            $table->string('name');
            $table->string('city');
            $table->text('address');
            $table->string('phone');
            $table->integer('branch_id')->unsigned();
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->integer('cso_id')->unsigned();
            $table->foreign('cso_id')->references('id')->on('csos');
            $table->string('cso_phone')->nullable();
            $table->dateTime('appointment', 0);
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
        $table->dropForeign(['branch_id']);
        $table->dropColumn('branch_id');
        $table->dropForeign(['cso_id']);
        $table->dropColumn('cso_id');
        Schema::dropIfExists('home_services');
    }
}
