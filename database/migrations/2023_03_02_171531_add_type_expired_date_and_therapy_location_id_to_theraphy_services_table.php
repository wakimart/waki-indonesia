<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeExpiredDateAndTherapyLocationIdToTheraphyServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('theraphy_services', function (Blueprint $table) {
            $table->enum("type", ["free", "sehat_bersama"]);
            $table->date('expired_date')->nullable();
            $table->unsignedInteger("therapy_location_id")->nullable();
            $table->foreign("therapy_location_id")->references("id")->on("therapy_locations");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('theraphy_services', function (Blueprint $table) {
            $table->dropColumn(['type','expired_date','therapy_location_id']);
            $table->dropForeign("therapy_location_id");
        });
    }
}
