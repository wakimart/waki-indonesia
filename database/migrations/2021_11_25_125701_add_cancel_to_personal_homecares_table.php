<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCancelToPersonalHomecaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal_homecares', function (Blueprint $table) {
            $table->boolean('is_cancel')->default(false);
            $table->text('cancel_desc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personal_homecares', function (Blueprint $table) {
            $table->dropColumn('is_cancel');
            $table->dropColumn('cancel_desc');
        });
    }
}
