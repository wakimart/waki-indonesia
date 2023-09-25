<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccDescToSubmissionVideoPhotoDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submission_video_photo_details', function (Blueprint $table) {
            $table->text('acc_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('submission_video_photo_details', function (Blueprint $table) {
            $table->dropColumn('acc_description');
        });
    }
}
