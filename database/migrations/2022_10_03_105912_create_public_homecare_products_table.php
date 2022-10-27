<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicHomecareProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_homecare_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("public_homecare_id");
            $table->foreign("public_homecare_id")->references("id")->on("public_homecares");
            $table->unsignedInteger("ph_product_id");
            $table->foreign("ph_product_id")->references("id")->on("personal_homecare_products");
            $table->unsignedInteger("checklist_out_id")->nullable();
            $table->foreign("checklist_out_id")->references("id")->on("personal_homecare_checklists");
            $table->unsignedInteger("checklist_in_id")->nullable();
            $table->foreign("checklist_in_id")->references("id")->on("personal_homecare_checklists");
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
        Schema::dropIfExists('public_homecare_products');
    }
}
