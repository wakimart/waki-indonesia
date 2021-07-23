<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('delivery_orders', function (Blueprint $table) {
        //     $table->boolean('active')->default(true);
        // });
        // Schema::table('orders', function (Blueprint $table) {
        //     $table->boolean('active')->default(true);
        // });
        // Schema::table('home_services', function (Blueprint $table) {
        //     $table->boolean('active')->default(true);
        // });
        // Schema::table('csos', function (Blueprint $table) {
        //     $table->boolean('active')->default(true);
        // });
        // Schema::table('branches', function (Blueprint $table) {
        //     $table->boolean('active')->default(true);
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_orders', function (Blueprint $table) {
            $table->dropColumn('active');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('active');
        });
        Schema::table('home_services', function (Blueprint $table) {
            $table->dropColumn('active');
        });
        Schema::table('csos', function (Blueprint $table) {
            $table->dropColumn('active');
        });
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
}
