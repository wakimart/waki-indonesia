<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropCsoIdOnDataSourcingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_sourcings', function (Blueprint $table) {
            $table->dropForeign(['cso_id']);
            $table->dropColumn('cso_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_sourcings', function (Blueprint $table) {
            $table->unsignedInteger('cso_id');
            $table->foreign('cso_id')->references('id')->on('csos');
        });
    }
}
