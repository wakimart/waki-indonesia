<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechnicianSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technician_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('appointment');
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->unsignedInteger("technician_id");
            $table->foreign("technician_id")->references("id")->on("csos");
            $table->unsignedInteger("home_service_id")->nullable();
            $table->foreign("home_service_id")->references("id")->on("home_services");
            $table->string("d_o")->nullable();
            $table->boolean("active")->default(true);
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
        Schema::table('techinician_schedules', function (Blueprint $table) {
            $table->dropForeign(['technician_id', 'home_service']);
        });
        Schema::dropIfExists('technician_schedules');
    }
}
