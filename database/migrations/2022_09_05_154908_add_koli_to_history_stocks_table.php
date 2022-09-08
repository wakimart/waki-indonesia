<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddKoliToHistoryStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE history_stocks DROP INDEX history_stocks_stock_id_foreign, 
            CHANGE stock_id stock_from_id INT(10) UNSIGNED NOT NULL');
        Schema::table('history_stocks', function (Blueprint $table) {
            $table->string('koli')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('stock_from_id')->references('id')->on('stocks');
            $table->unsignedInteger('stock_to_id')->nullable()->after('stock_from_id');
            $table->foreign('stock_to_id')->references('id')->on('stocks');
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE history_stocks DROP INDEX history_stocks_stock_from_id_foreign, 
            CHANGE stock_from_id stock_id INT(10) UNSIGNED NOT NULL');
        Schema::table('history_stocks', function (Blueprint $table) {
            $table->dropForeign([
                'history_stocks_user_id_foreign',
                'history_stocks_stock_to_id_foreign',
            ]);
            $table->foreign('stock_id')->references('id')->on('stocks');
            $table->dropColumn(['koli', 'user_id', 'stock_to_id', 'active']);
        });
    }
}
