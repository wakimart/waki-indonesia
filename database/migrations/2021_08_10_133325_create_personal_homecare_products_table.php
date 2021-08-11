<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalHomecareProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_homecare_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string("code", 20);
            $table->unsignedInteger("branch_id");
            $table->foreign("branch_id")->references("id")->on("branches");
            $table->unsignedInteger("product_id");
            $table->foreign("product_id")->references("id")->on("products");
            $table->boolean("status")->default(1);
            $table->boolean("active")->default(1);
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
        Schema::table("personal_homecare_products", function (Blueprint $table) {
            $table->dropForeign(["branch_id", "product_id"]);
        });
        Schema::dropIfExists('personal_homecare_products');
    }
}
