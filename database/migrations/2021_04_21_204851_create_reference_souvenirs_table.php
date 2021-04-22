<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferenceSouvenirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("reference_souvenirs", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("reference_id")->unsigned();
            $table->foreign("reference_id")->references("id")->on("references");
            $table->integer("souvenir_id")->unsigned();
            $table->foreign("souvenir_id")->references("id")->on("souvenirs");
            $table->integer("link_hs");
            $table->enum("status", ["pending", "success"])->default("pending");
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
        Schema::dropIfExists("reference_souvenirs");
    }
}
