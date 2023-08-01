<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommissionToOrderCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_commissions', function (Blueprint $table) {
            $table->decimal('commission', 20, 2)->default(0)->after('cso_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_commissions', function (Blueprint $table) {
            $table->dropColumn('commission');
        });
    }
}
