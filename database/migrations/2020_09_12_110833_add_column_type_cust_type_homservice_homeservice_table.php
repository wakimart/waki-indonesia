<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTypeCustTypeHomserviceHomeserviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home_services', function (Blueprint $table) {
            $table->enum('type_customer', ['Tele Voucher', 'Tele Home Service', 'Home Office Voucher', 'Home Voucher']);
            $table->enum('type_homeservices', ['Home service', 'Upgrade Member', 'Home Eksklusif Therapy', 'Home Family Therapy']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_services', function (Blueprint $table){
            $table->dropColum('type_customer');
            $table->dropColum('type_homeservices');
        });
    }
}
