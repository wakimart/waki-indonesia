<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->increments('id');
            $table->string("code");
            $table->string("name", 200);
            $table->string("address", 300);
            $table->integer("province_id");
            $table->integer("city_id");
            $table->integer("subdistrict_id");
            $table->string("description")->nullable();
            $table->unsignedInteger("parent_warehouse_id")->nullable();
            $table->boolean("active")->default(1);
            $table->timestamps();
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->foreign("parent_warehouse_id")
                ->references("id")
                ->on("warehouses");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropForeign(['parent_warehouse_id']);
        });
        Schema::dropIfExists('warehouses');
    }
}
