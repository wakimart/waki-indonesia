<?php

use App\Acceptance;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAreaToAcceptancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acceptances', function (Blueprint $table) {
            $table->enum('area', ['surabaya', 'jakarta'])->nullable()->default(null);
        });
        Acceptance::query()->where('status', 'approved')->update(['area' => 'surabaya']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acceptances', function (Blueprint $table) {
            $table->dropColumn('area');
        });
    }
}
