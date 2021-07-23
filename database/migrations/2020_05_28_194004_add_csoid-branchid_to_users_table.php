<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCsoidBranchidToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dateTime('birth_date', 0)->nullable();
        //     $table->string('branches_id')->nullable();
        //     $table->integer('cso_id')->unsigned()->nullable();
        //     $table->foreign('cso_id')->references('id')->on('csos');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('birth_date');
            $table->dropColumn('branches_id');            
            $table->dropForeign(['cso_id']);
            $table->dropColumn('cso_id');
        });
    }
}
