<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalHomecaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_homecares', function (Blueprint $table) {
            $table->increments('id');
            $table->enum(
                    "status",
                    [
                        "new",
                        "approve_out",
                        "process",
                        "waiting_in",
                        "done",
                        "rejected",
                    ]
                )
                ->default("new");
            $table->date("schedule");
            $table->string("name");
            $table->string("phone", 20);
            $table->string("address", 200);
            $table->integer("province_id");
            $table->integer("city_id");
            $table->integer("subdistrict_id");
            $table->unsignedInteger("branch_id");
            $table->foreign("branch_id")->references("id")->on("branches");
            $table->unsignedInteger("cso_id");
            $table->foreign("cso_id")->references("id")->on("csos");
            $table->string("id_card");

            $table->unsignedInteger("ph_product_id");
            $table->foreign("ph_product_id")
                ->references("id")
                ->on("personal_homecare_products");

            $table->unsignedInteger("checklist_out");
            $table->foreign("checklist_out")
                ->references("id")
                ->on("personal_homecare_checklists");

            $table->unsignedInteger("checklist_in")->nullable();
            $table->foreign("checklist_in")
                ->references("id")
                ->on("personal_homecare_checklists");


            $table->boolean("active")->default(1);
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
        Schema::table("personal_homecares", function (Blueprint $table) {
            $table->dropForeign(["branch_id", "cso_id", "checklist_out", "checklist_in"]);
        });
        Schema::dropIfExists('personal_homecares');
    }
}
