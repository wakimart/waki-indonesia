<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFkColumnToTherapyLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('therapy_locations', function (Blueprint $table) {
            $table->unsignedInteger("branch_id")->nullable();
            $table->foreign("branch_id")->references("id")->on("branches");
            $table->integer("province_id")->nullable();
            $table->integer("city_id")->nullable();
            $table->integer("subdistrict_id")->nullable();
            $table->string("address", 400)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('therapy_locations', function (Blueprint $table) {
            $table->dropForeign('branch_id');
            $table->dropColumn(['branch_id', 'province_id', 'city_id', 'subdistrict_id', 'address']);
        });
    }
}
