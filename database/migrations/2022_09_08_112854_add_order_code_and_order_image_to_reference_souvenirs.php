<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderCodeAndOrderImageToReferenceSouvenirs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reference_souvenirs', function (Blueprint $table) {
            $table->text('order_code')->nullable();
            $table->text('order_image')->nullable();
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
            $table->dropColumn(['order_code', 'order_image']);
        });
    }
}
