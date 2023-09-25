<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWakimartLinkToSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('wakimart_link')->nullable();
        });
        Schema::table('reference_souvenirs', function (Blueprint $table) {
            $table->integer('theraphy_service_id')->unsigned()->nullable();
            $table->foreign('theraphy_service_id')->references('id')->on('theraphy_services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('wakimart_link');
        });
        Schema::table('reference_souvenirs', function (Blueprint $table) {
            $table->dropForeign(['theraphy_service_id']);
            $table->dropColumn('theraphy_service_id');
        });
    }
}
