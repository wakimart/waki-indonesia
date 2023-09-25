<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmissionVideoPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submission_video_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("branch_id");
            $table->foreign("branch_id")->references("id")->on("branches");
            $table->date('submission_date');
            $table->enum('type', ['Testimonial Video Photo']);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
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
        Schema::dropIfExists('submission_video_photos');
    }
}
