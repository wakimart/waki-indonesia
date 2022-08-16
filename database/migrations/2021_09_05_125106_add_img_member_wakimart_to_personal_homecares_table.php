<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImgMemberWakimartToPersonalHomecaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal_homecares', function (Blueprint $table) {
            $table->string("member_wakimart");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personal_homecares', function (Blueprint $table) {
            $table->dropColumn("member_wakimart");
        });
    }
}
