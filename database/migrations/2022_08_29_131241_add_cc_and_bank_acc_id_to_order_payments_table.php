<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCcAndBankAccIdToOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_payments', function (Blueprint $table) {
            $table->enum('type', ['order', 'cash', 'delivery']);
            $table->enum('type_payment', ['cash', 'debit', 'card', 'card installment']);
            $table->unsignedInteger("credit_card_id")->nullable();
            $table->foreign("credit_card_id")->references("id")->on("credit_cards");
            $table->double('charge_percentage_bank')->default(0);
            $table->double('charge_percentage_company')->default(0);
            $table->date('estimate_transfer_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_payments', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('type_payment');
            $table->dropColumn('credit_card_id');
            $table->dropForeign('credit_card_id');
            $table->dropColumn('charge_percentage_bank');
            $table->dropColumn('charge_percentage_company');
            $table->dropColumn('estimate_transfer_date');
        });
    }
}
