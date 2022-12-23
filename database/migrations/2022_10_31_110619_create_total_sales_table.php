<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTotalSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("order_payment_id")->nullable();
            $table->foreign("order_payment_id")->references("id")->on("order_payments");
            $table->decimal('bank_in', 20, 2)->default(0);
            $table->decimal('debit', 20, 2)->default(0);
            $table->decimal('netto_debit', 20, 2)->default(0);
            $table->decimal('card', 20, 2)->default(0);
            $table->decimal('netto_card', 20, 2)->default(0);
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
        Schema::dropIfExists('total_sales');
    }
}
