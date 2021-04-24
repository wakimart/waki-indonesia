<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionDeliveryordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("submission_deliveryorders", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("submission_id")->unsigned();
            $table->foreign("submission_id")->references("id")->on("submissions");
            $table->string("no_deliveryorder");
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
        Schema::dropIfExists("submission_deliveryorders");
    }
}
