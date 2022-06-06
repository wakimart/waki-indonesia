<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTypeCustomerIdOnDataSourcingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_sourcings', function (Blueprint $table) {
            $table->dropForeign(['type_customer_id']);
            $table->dropColumn('type_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_sourcings', function (Blueprint $table) {
            $table->unsignedInteger('type_customer_id');
            $table->foreign('type_customer_id')->references('id')->on('type_customers');
        });
    }
}
