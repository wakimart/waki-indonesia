<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusReferenceToReferenceSouvenirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reference_souvenirs', function (Blueprint $table) {
            $table->enum("final_status", ["pending", "success"])->default("pending");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reference_souvenirs', function (Blueprint $table) {
            $table->dropColumn('final_status');
        });
    }
}
