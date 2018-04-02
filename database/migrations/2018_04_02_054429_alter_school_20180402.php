<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSchool20180402 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('school', function (Blueprint $table) {
            $table->string('thumbs_up',1000)->after("check_status")->default("");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('school', function (Blueprint $table) {
            $table->dropColumn('thumbs_up');
        });
    }
}
