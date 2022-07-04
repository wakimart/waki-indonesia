<?php

use App\Order;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSystemOrderToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['new', 'process', 'delivery', 'success', 'reject'])->default('new');
            $table->json('delivery_cso_id')->nullable();
        });
        Order::query()->update(['status' => 'success']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['status', 'delivery_cso_id']);
        });
    }
}
