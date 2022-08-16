<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRescheduleAndExtendToPersonalHomecaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal_homecares', function (Blueprint $table) {
            $table->boolean('is_extend')->default(false);
            $table->date('reschedule_date')->nullable();
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
            $table->dropColumn('is_extend');
            $table->dropColumn('reschedule_date');
        });
    }
}
