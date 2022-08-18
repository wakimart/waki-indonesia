<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeometryDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geometry_districts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('distric')->nullable();
            $table->unsignedInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions');
            $table->longText('geom');
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
        Schema::dropIfExists('geometry_districts');
    }
}
