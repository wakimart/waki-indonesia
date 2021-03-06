<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrizeIdToReferenceSouvenirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reference_souvenirs', function (Blueprint $table) {
            $table->enum("delivery_status_prize", ["undelivered", "delivered"])
                ->nullable()
                ->after("status");
            $table->enum("status_prize", ["pending", "success"])
                ->nullable()
                ->after("status");
            $table->integer("prize_id")
                ->unsigned()
                ->nullable()
                ->after("status");
            $table->foreign("prize_id")
                ->references("id")
                ->on("prizes");
            $table->integer("order_id")
                ->unsigned()
                ->nullable()
                ->after("status");
            $table->foreign("order_id")
                ->references("id")
                ->on("orders");
            $table->enum("delivery_status_souvenir", ["undelivered", "delivered"])
                ->nullable()
                ->after("status");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reference_souvenirs', function (Blueprint $table) {
            $table->dropForeign(["prize_id", "order_id"]);
            $table->dropColumn("delivery_status_souvenir");
            $table->dropColumn("order_id");
            $table->dropColumn("prize_id");
            $table->dropColumn("status_prize");
            $table->dropColumn("delivery_status_prize");
        });
    }
}
