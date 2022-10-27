<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsentOffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absent_offs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('division');
            $table->string('duration_work');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration_off');
            $table->date('work_on');
            $table->longText('desc');
            $table->unsignedInteger("cso_id");
            $table->foreign("cso_id")->references("id")->on("csos");
            $table->unsignedInteger("branch_id");
            $table->foreign("branch_id")->references("id")->on("branches");
            $table->unsignedInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->unsignedInteger("supervisor_id")->nullable();
            $table->foreign("supervisor_id")->references("id")->on("users");
            $table->unsignedInteger("coordinator_id")->nullable();
            $table->foreign("coordinator_id")->references("id")->on("users");
            $table->enum('status', ['new', 'approved', 'rejected'])->default('new');
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
        Schema::dropIfExists('absent_offs');
    }
}
