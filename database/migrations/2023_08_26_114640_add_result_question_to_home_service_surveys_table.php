<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResultQuestionToHomeServiceSurveysTable extends Migration
{
    public function __construct()
    {
        $this->fromIdx = 4;
        $this->toIdx = 1;
    }

    /**
     * Run the migrations.
     *
     * @return void1
     */
    public function up()
    {
        Schema::table('home_service_surveys', function (Blueprint $table) {
            $table->dropColumn('result');
            for ($i = $this->fromIdx; $i >= $this->toIdx; $i--) { 
                $table->integer('result_quest_'.$i)->nullable()->after("home_service_id");
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_service_surveys', function (Blueprint $table) {
            $table->text('result')->after("home_service_id");
            for ($i = $this->fromIdx; $i >= $this->toIdx; $i--) { 
                $table->dropColumn('result_quest_'.$i);
            }
        });
    }
}
