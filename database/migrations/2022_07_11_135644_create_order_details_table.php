<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("order_id");
            $table->foreign("order_id")->references("id")->on("orders");
            $table->unsignedInteger("product_id")->nullable();
            $table->foreign("product_id")->references("id")->on("products");
            $table->unsignedInteger("promo_id")->nullable();
            $table->foreign("promo_id")->references("id")->on("promos");
            $table->integer("qty");
            $table->enum("type", ["pembelian", "prize", "upgrade"]);
            $table->string('other')->nullable();
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
        // Schema::dropIfExists('order_details');
    }
}
