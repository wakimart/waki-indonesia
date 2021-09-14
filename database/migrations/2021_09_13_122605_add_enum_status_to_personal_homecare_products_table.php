<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnumStatusToPersonalHomecareProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `personal_homecare_products` CHANGE `status` `status` VARCHAR(50) NOT NULL DEFAULT 'pending';");

        \DB::statement("UPDATE `personal_homecare_products` SET `status`='available' WHERE status = '1';");

        \DB::statement("UPDATE `personal_homecare_products` SET `status`='unavailable' WHERE status = '0';");
        
        \DB::statement("ALTER TABLE `personal_homecare_products` CHANGE `status` `status` ENUM('pending','available','unavailable') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending';");
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
