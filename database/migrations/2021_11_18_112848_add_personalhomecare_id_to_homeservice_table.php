<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPersonalhomecareIdToHomeserviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_services', function (Blueprint $table) {
            $table->integer('personalhomecare_id')->unsigned()->nullable();
            $table->foreign('personalhomecare_id')->references('id')->on('personal_homecares');
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
            $table->dropForeign(['personalhomecare_id']);
            $table->dropColumn('personalhomecare_id');
        });
    }
}
