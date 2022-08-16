<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWakimartLinkToReferenceSouvenirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reference_souvenirs', function (Blueprint $table) {
            $table->text('wakimart_link')->nullable();
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
            $table->dropColumn('wakimart_link');
        });
    }
}
