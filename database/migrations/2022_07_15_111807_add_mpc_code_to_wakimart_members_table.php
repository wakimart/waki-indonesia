<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMpcCodeToWakimartMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wakimart_members', function (Blueprint $table) {
            $table->string('mpc_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wakimart_members', function (Blueprint $table) {
            $table->dropColumn('mpc_code');
        });
    }
}
