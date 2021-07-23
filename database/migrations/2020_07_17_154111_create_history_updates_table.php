<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('history_updates', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('type_menu');
        //     $table->string('method');
        //     $table->json('meta');
        //     $table->unsignedInteger('user_id');
        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        //     $table->timestamps();
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
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            Schema::dropIfExists('history_updates');
        });
        
    }
}
