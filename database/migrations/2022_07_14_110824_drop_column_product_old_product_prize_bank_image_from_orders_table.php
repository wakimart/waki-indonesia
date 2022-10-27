<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnProductOldProductPrizeBankImageFromOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['product', 'old_product', 'prize', 'bank', 'image']);
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('product');
            $table->string('old_product')->nullable();
            $table->string('prize')->nullable();
            $table->string('bank')->nullable();
            $table->string('image')->nullable();
        });
    }
}
