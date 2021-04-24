<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferenceImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("reference_images", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("reference_id")->unsigned();
            $table->foreign("reference_id")->references("id")->on("references");
            $table->string("image_1");
            $table->string("image_2")->nullable();
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
        Schema::dropIfExists("reference_images");
    }
}
