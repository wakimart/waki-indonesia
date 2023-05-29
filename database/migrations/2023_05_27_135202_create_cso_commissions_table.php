<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsoCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cso_commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cso_id');
            $table->foreign('cso_id')->references('id')->on('csos');
            $table->decimal('commission', 20, 2)->default(0);
            $table->decimal('pajak', 20, 2)->default(0);
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
        Schema::dropIfExists('cso_commissions');
    }
}
