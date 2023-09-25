<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTheraphyServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theraphy_services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->date('registered_date')->useCurrent();
            $table->string('name');
            $table->string("phone", 20);
            $table->integer("province_id")->nullable();
            $table->integer("city_id")->nullable();
            $table->integer("subdistrict_id")->nullable();
            $table->string("address", 400)->nullable();
            $table->string("email_facebook", 200)->nullable();
            $table->longtext("meta_condition")->nullable();
            $table->enum("status", ["process", "success", "reject"])->default("process");
            $table->boolean('active')->default(1);
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
        Schema::dropIfExists('theraphy_services');
    }
}
