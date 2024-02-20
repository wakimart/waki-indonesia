<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddNominalCancelAndUpdateStatusToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE orders MODIFY status enum('new','process','delivery','success','reject','stock_request_pending','stock_request_success','delivered','cancel')");
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('nominal_cancel', 20, 2)->nullable();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE orders MODIFY status enum('new','process','delivery','success','reject','stock_request_pending','stock_request_success','delivered')");
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('nominal_cancel');
        });
    }
}
