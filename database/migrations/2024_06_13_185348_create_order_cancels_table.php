<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderCancelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_cancels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("order_id")->nullable();
            $table->foreign("order_id")->references("id")->on("orders");
            $table->unsignedInteger("branch_id")->nullable();
            $table->foreign("branch_id")->references("id")->on("branches");
            $table->unsignedInteger("cso_id")->nullable();
            $table->foreign("cso_id")->references("id")->on("csos");
            $table->string('temp_no')->nullable();
            $table->date('cancel_date');
            $table->decimal('nominal_cancel', 20, 2)->default(0);
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
        Schema::dropIfExists('order_cancels');
    }
}
