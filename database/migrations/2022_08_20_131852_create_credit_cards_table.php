<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('name');
            $table->unsignedInteger("bank_account_id");
            $table->foreign("bank_account_id")->references("id")->on("bank_accounts");
            $table->integer('cicilan');
            $table->double('charge_percentage_sales')->default(0);
            $table->double('charge_percentage_company')->default(0);
            $table->integer('estimate_transfer')->default(0);
            $table->text('description')->nullable();
            $table->boolean('active')->default(1);
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
        Schema::dropIfExists('credit_cards');
    }
}
