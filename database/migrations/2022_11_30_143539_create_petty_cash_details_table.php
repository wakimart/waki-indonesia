<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePettyCashDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petty_cash_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('petty_cash_id');
            $table->foreign('petty_cash_id')->references('id')->on('petty_cashes');
            $table->unsignedInteger('petty_cash_out_bank_account_id')->nullable();
            $table->foreign('petty_cash_out_bank_account_id')->references('id')->on('bank_accounts');
            $table->unsignedInteger('petty_cash_out_type_id')->nullable();
            $table->foreign('petty_cash_out_type_id')->references('id')->on('petty_cash_out_types');
            $table->decimal('nominal', 20, 2)->default(0);
            $table->longText('description')->nullable();
            $table->longText('evidence_image')->nullable();
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
        Schema::dropIfExists('petty_cash_details');
    }
}
