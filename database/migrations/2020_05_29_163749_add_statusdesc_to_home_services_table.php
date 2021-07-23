<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusdescToHomeServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('home_services', function (Blueprint $table) {
        //     $table->boolean('cash')->nullable();
        //     $table->string('cash_description', 220)->nullable();
        //     $table->string('description', 700)->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_services', function (Blueprint $table) {
            $table->dropColumn('cash');
            $table->dropColumn('cash_description');
            $table->dropColumn('description');
        });
    }
}
