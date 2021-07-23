<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUpgradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upgrades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('acceptance_id')->nullable()->unsigned();
            $table->foreign('acceptance_id')->references('id')->on('acceptances');
            $table->dateTime('due_date', 0)->nullable();
            $table->enum('status', ['New', 'Process', 'Approval', 'Complete', 'Reject'])->nullable();
            $table->string('history_status')->nullable();
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
        Schema::dropIfExists('upgrades');
    }
}
