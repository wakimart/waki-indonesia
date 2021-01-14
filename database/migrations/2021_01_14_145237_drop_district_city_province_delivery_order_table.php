<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropDistrictCityProvinceDeliveryOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_orders', function (Blueprint $table){
            $table->dropColumn('province');
            $table->dropColumn('city');
            $table->dropColumn('distric');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_orders', function (Blueprint $table){
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('distric')->nullable();
        });
    }
}
