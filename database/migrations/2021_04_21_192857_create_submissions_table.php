<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("submissions", function (Blueprint $table) {
            $table->increments("id")->unsigned();
            $table->string("code");
            $table->string("no_member")->nullable();
            $table->integer("branch_id")->unsigned();
            $table->foreign("branch_id")->references("id")->on("branches");
            $table->integer("cso_id")->unsigned();
            $table->foreign("cso_id")->references("id")->on("csos");
            $table->enum("type", ["mgm", "referensi", "takeaway"]);
            $table->string("name");
            $table->text("address");
            $table->string("phone");
            $table->integer("province");
            $table->integer("city");
            $table->integer("district");
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
        Schema::dropIfExists("submissions");
    }
}
