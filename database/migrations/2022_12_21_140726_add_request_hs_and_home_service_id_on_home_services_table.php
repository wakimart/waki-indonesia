<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequestHsAndHomeServiceIdOnHomeServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('request_hs')->nullable();
            $table->unsignedInteger("home_service_id")->nullable();
            $table->foreign("home_service_id")->references("id")->on("home_services");
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
            $table->dropForeign('orders_home_service_id_foreign');
            $table->dropColumn(['request_hs','home_service_id']);
        });
    }
}
