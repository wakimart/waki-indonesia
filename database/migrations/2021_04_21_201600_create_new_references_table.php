<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists("references");
        Schema::create("references", function (Blueprint $table) {
            $table->increments("id")->unsigned();
            $table->integer("submission_id")->unsigned();
            $table->foreign("submission_id")->references("id")->on("submissions");
            $table->string("name");
            $table->integer("age");
            $table->string("phone");
            $table->integer("province");
            $table->integer("city");
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
        Schema::dropIfExists("references");
    }
}
