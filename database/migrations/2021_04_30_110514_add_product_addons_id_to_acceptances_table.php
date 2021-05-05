<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductAddonsIdToAcceptancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acceptances', function (Blueprint $table) {
            $table->integer("product_addons_id")
                ->unsigned()
                ->nullable();
            $table->foreign("product_addons_id")
                ->references("id")
                ->on("products");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acceptances', function (Blueprint $table) {
            $table->dropForeign(["product_addons_id"]);
            $table->dropColumn("product_addons_id");
        });
    }
}
