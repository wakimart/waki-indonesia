<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCancelToCsoCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cso_commissions', function (Blueprint $table) {
            $table->decimal('cancel', 20, 2)->default(0)->after('commission');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cso_commissions', function (Blueprint $table) {
            $table->dropColumn('cancel');
        });
    }
}
