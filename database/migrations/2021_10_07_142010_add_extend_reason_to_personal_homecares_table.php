<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtendReasonToPersonalHomecaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal_homecares', function (Blueprint $table) {
            // $table->string('extend_reason')->nullable();
            $table->string('reschedule_reason')->nullable();
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
            // $table->dropColumn('extend_reason');
            $table->dropColumn('reschedule_reason');
        });
    }
}
