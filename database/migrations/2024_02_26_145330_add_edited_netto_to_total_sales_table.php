<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEditedNettoToTotalSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('total_sales', function (Blueprint $table) {
            $table->decimal('netto_debit_edited', 20, 2)->nullable()->after('netto_debit');
            $table->decimal('netto_card_edited', 20, 2)->nullable()->after('netto_card');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('total_sales', function (Blueprint $table) {
            $table->dropColumn('netto_debit_edited');
            $table->dropColumn('netto_card_edited');
        });
    }
}
