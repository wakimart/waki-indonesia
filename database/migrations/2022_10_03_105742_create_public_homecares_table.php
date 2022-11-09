<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicHomecaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_homecares', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('status', ['new', 'verified', 'approve_out', 'process', 'waiting_in', 'done', 'rejected']);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('name');
            $table->string('phone');
            $table->text('address');
            $table->integer("province_id");
            $table->integer("city_id");
            $table->integer("district_id");
            $table->integer('branch_id')->unsigned();
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->integer('cso_id')->unsigned();
            $table->foreign('cso_id')->references('id')->on('csos');
            $table->integer('cso_optional_id')->unsigned()->nullable();
            $table->foreign('cso_optional_id')->references('id')->on('csos');
            $table->string('approval_letter');
            $table->text('other_product');
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
        Schema::dropIfExists('public_homecares');
    }
}
