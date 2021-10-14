<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnumTypehsToHomeServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `home_services` CHANGE `type_homeservices` `type_homeservices` ENUM('Program Penjelas Ulang','Home service','Home Tele Voucher','Home Eksklusif Therapy','Home Free Family Therapy','Home Demo Health & Safety with WAKi','Home Voucher','Home Tele Free Gift','Home Refrensi Product','Home Delivery','Home Free Refrensi Therapy VIP','Home WAKi di Rumah Aja','Program Pinjamin 5 Hari') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
