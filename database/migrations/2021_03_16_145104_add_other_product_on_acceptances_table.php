<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOtherProductOnAcceptancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acceptances', function (Blueprint $table) {
            $table->dropForeign(['oldproduct_id']);
            $table->dropColumn('oldproduct_id');
            $table->string('other_product')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acceptances', function (Blueprint $table) {
            $table->integer('oldproduct_id')->unsigned();
            $table->foreign('oldproduct_id')->references('id')->on('products');
            $table->dropColumn('other_product');
        });
    }
}
