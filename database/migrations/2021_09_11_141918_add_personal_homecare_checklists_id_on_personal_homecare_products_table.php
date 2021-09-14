<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPersonalHomecareChecklistsIdOnPersonalHomecareProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal_homecare_products', function (Blueprint $table) {
            $table->integer('current_checklist_id')->unsigned()->nullable();
            $table->foreign('current_checklist_id')->references('id')->on('personal_homecare_checklists');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personal_homecare_products', function (Blueprint $table) {
            $table->dropForeign(['current_checklist_id']);
            $table->dropColumn('current_checklist_id');
        });
    }
}
