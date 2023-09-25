<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTherapyServiceSouvenirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('therapy_service_souvenirs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("therapy_service_id");
            $table->foreign("therapy_service_id")->references("id")->on("theraphy_services");
            $table->unsignedInteger("souvenir_id");
            $table->foreign("souvenir_id")->references("id")->on("souvenirs");
            $table->unsignedInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
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
        Schema::dropIfExists('therapy_service_souvenirs');
    }
}
