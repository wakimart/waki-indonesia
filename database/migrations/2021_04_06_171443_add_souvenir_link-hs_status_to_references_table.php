<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSouvenirLinkHsStatusToReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('references', function (Blueprint $table) {
            $table->enum("status", ["pending", "success"])
                ->nullable()
                ->after("deliveryorder_id");
            $table->string("link_hs")
                ->nullable()
                ->after("deliveryorder_id");
            $table->integer("souvenir_id")
                ->unsigned()
                ->nullable()
                ->after("deliveryorder_id");
            $table->foreign("souvenir_id")
                ->references("id")
                ->on("souvenirs");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('references', function (Blueprint $table) {
            $table->dropForeign(["souvenir_id"]);
            $table->dropColumn("souvenir_id");
            $table->dropColumn("link_hs");
            $table->dropColumn("status");
        });
    }
}
