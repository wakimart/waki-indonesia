<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRescheduleToHomeServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_services', function (Blueprint $table) {
            $table->boolean('is_acc_resc')->default(false);
            $table->text('resc_desc')->nullable();
            $table->dateTime('resc_acc')->nullable();
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
            $table->dropColumn(['is_acc_resc', 'resc_desc', 'resc_acc']);
        });
    }
}
