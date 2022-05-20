<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTechnicianSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_technician_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('due_date', 0);
            $table->string('issues')->nullable();
            $table->string('other_product')->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedInteger("technician_schedule_id")->nullable();
            $table->foreign("technician_schedule_id")->references("id")->on("technician_schedules");
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
        Schema::table('product_technician_schedules', function (Blueprint $table) {
            $table->dropForeign(['product_id', 'technician_schedule_id']);
        });
        Schema::dropIfExists('product_technician_schedules');
    }
}
