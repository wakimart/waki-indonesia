<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('users', function (Blueprint $table) {
        //     $table->string('code');
        //     $table->boolean('active')->default(true);
        //     $table->text('permissions')->nullable();
        //     $table->string('user_image')->nullable();
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
            $table->dropColumn('code');
            $table->dropColumn('active');
            $table->dropColumn('permissions');
            $table->dropColumn('user_image');
        });
    }
}
