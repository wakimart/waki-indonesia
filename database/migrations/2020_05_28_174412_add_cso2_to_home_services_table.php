<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCso2ToHomeServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_services', function (Blueprint $table) {
            $table->integer('cso2_id')->unsigned()->nullable();
            $table->foreign('cso2_id')->references('id')->on('csos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_services', function (Blueprint $table) {
            $table->dropForeign(['cso2_id']);
            $table->dropColumn('cso2_id');
        });
    }
}
