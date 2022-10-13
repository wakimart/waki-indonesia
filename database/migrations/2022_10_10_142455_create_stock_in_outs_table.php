<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockInOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_in_outs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('warehouse_from_id');
            $table->foreign('warehouse_from_id')->references('id')->on('warehouses');
            $table->unsignedInteger('warehouse_to_id');
            $table->foreign('warehouse_to_id')->references('id')->on('warehouses');
            $table->string('code');
            $table->string('temp_no')->nullable();
            $table->date('date');
            $table->enum('type', ['in', 'out']);
            $table->longText('description')->nullable();
            $table->unsignedInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->timestamps();
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_in_outs');
    }
}
