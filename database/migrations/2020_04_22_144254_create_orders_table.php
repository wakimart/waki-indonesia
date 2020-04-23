<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('no_member')->nullable();
            $table->string('name');
            $table->string('address');
            $table->string('cash_upgrade');
            $table->string('product');
            $table->string('old_product')->nullable();
            $table->string('prize')->nullable();
            $table->string('payment_type');
            $table->string('bank')->nullable();
            $table->decimal('total_payment', 20, 2);
            $table->string('down_payment')->nullable();
            $table->string('remaining_payment')->nullable();
            $table->integer('cso_id')->unsigned();
            $table->foreign('cso_id')->references('id')->on('csos');
            $table->integer('branch_id')->unsigned();
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->integer('30_cso_id')->unsigned()->nullable();
            $table->foreign('30_cso_id')->references('id')->on('csos');
            $table->integer('70_cso_id')->unsigned()->nullable();
            $table->foreign('70_cso_id')->references('id')->on('csos');
            $table->string('customer_type')->nullable();
            $table->string('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
