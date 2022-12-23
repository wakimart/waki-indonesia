<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinancialRoutineTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_routine_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->date('transaction_date');
            $table->unsignedInteger("bank_account_id");
            $table->foreign("bank_account_id")->references("id")->on("bank_accounts");
            $table->unsignedInteger("financial_routine_id");
            $table->foreign("financial_routine_id")->references("id")->on("financial_routines");
            $table->text('description')->nullable();
            $table->decimal('transaction', 20, 2)->default(0);
            $table->unsignedInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
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
        Schema::dropIfExists('financial_routine_transactions');
    }
}
