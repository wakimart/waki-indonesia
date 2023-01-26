<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequestHsAccToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('request_hs_acc')->nullable();
        });

        Schema::table('order_homeservices', function (Blueprint $table) {
            $table->boolean('delivery')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('request_hs_acc');
        });

        Schema::table('order_homeservices', function (Blueprint $table) {
            $table->dropColumn('delivery');
        });
    }
}
