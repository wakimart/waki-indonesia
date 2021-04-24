<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("submission_images", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("submission_id")->unsigned();
            $table->foreign("submission_id")->references("id")->on("submissions");
            $table->string("image_1");
            $table->string("image_2")->nullable();
            $table->string("image_3")->nullable();
            $table->string("image_4")->nullable();
            $table->string("image_5")->nullable();
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
        Schema::dropIfExists("submission_images");
    }
}
