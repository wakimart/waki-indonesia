<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinancialRoutinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_routines', function (Blueprint $table) {
            $table->increments('id');
            $table->date('routine_date');
            $table->unsignedInteger("bank_account_id");
            $table->foreign("bank_account_id")->references("id")->on("bank_accounts");
            $table->unsignedInteger("financial_routine_id")->nullable();
            $table->foreign("financial_routine_id")->references("id")->on("financial_routines");
            $table->decimal('total_sale', 20, 2)->default(0);
            $table->decimal('bank_interest', 20, 2)->default(0);
            $table->decimal('bank_tax', 20, 2)->default(0);
            $table->decimal('etc_in', 20, 2)->default(0);
            $table->decimal('etc_out', 20, 2)->default(0);
            $table->decimal('remains_saldo', 20, 2)->default(0);
            $table->decimal('remains_sales', 20, 2)->default(0);
            $table->text('description')->nullable();
            $table->unsignedInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('financial_routines');
    }
}
