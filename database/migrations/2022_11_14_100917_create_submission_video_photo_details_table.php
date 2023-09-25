<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmissionVideoPhotoDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submission_video_photo_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("submission_video_photo_id");
            $table->foreign("submission_video_photo_id")->references("id")->on("submission_video_photos");
            $table->unsignedInteger("cso_id");
            $table->foreign("cso_id")->references("id")->on("csos");
            $table->longText('url_drive')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->date('detail_date')->nullable();
            $table->string('mpc_wakimart')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->longText('address')->nullable();
            $table->string('souvenir')->nullable();
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
        Schema::dropIfExists('submission_video_photo_details');
    }
}
