<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockInOutProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_in_out_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("stock_in_out_id");
            $table->foreign("stock_in_out_id")->references("id")->on("stock_in_outs");
            $table->unsignedInteger('stock_from_id');
            $table->foreign('stock_from_id')->references('id')->on('stocks');
            $table->unsignedInteger('stock_to_id');
            $table->foreign('stock_to_id')->references('id')->on('stocks');
            $table->unsignedInteger("product_id");
            $table->foreign("product_id")->references("id")->on("products");
            $table->integer('quantity');
            $table->integer('koli')->nullable();
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
        Schema::dropIfExists('stock_in_out_products');
    }
}
