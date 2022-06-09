<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataTherapiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_therapies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('no_ktp')->nullable();
            $table->string('img_ktp')->nullable();
            $table->unsignedInteger("branch_id")->nullable();
            $table->foreign("branch_id")->references("id")->on("branches");
            $table->unsignedInteger("cso_id")->nullable();
            $table->foreign("cso_id")->references("id")->on("csos");
            $table->string('phone')->nullable();
            $table->text("address")->nullable();
            $table->unsignedInteger("type_customer_id")->nullable();
            $table->foreign("type_customer_id")->references("id")->on("type_customers");
            $table->unsignedInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users");
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
        Schema::dropIfExists('data_therapies');
    }
}
