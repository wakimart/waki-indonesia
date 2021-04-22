<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferencePromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("reference_promos", function (Blueprint $table) {
            $table->increments("id")->unsigned();
            $table->integer("reference_id")->unsigned();
            $table->foreign("reference_id")->references("id")->on("references");
            $table->integer("promo_1")->unsigned()->nullable();
            $table->foreign("promo_1")->references("id")->on("promos");
            $table->integer("promo_2")->unsigned()->nullable();
            $table->foreign("promo_2")->references("id")->on("promos");
            $table->integer("qty_1")->nullable();
            $table->integer("qty_2")->nullable();
            $table->string("other_1")->nullable();
            $table->string("other_2")->nullable();
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
        Schema::dropIfExists("reference_promos");
    }
}
