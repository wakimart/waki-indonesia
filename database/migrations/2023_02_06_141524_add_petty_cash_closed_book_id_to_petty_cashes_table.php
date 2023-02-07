<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPettyCashClosedBookIdToPettyCashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('petty_cashes', function (Blueprint $table) {
            $table->unsignedInteger("petty_cash_closed_book_id")->nullable();
            $table->foreign("petty_cash_closed_book_id")->references("id")->on("petty_cash_closed_books");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('petty_cashes', function (Blueprint $table) {
            $table->dropForeign('petty_cashes_petty_cash_closed_book_id_foreign');
            $table->dropColumn('petty_cash_closed_book_id');
        });
    }
}
