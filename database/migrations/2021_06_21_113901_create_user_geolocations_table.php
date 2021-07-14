<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserGeolocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_geolocations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->json("presence_image")->nullable();
            $table->timestamp("date")->nullable();
            $table->string("filename")->nullable();
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
        Schema::table('user_geolocations', function (Blueprint $table) {
            $table->dropForeign(["user_id"]);
        });
        Schema::dropIfExists('user_geolocations');
    }
}
