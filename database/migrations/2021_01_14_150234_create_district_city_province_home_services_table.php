<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictCityProvinceHomeServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_services', function (Blueprint $table){
            $table->integer('province')->nullable();
            $table->integer('city')->nullable();
            $table->integer('distric')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_services', function (Blueprint $table){
            $table->dropColumn('province');
            $table->dropColumn('city');
            $table->dropColumn('distric');
        });
    }
}
