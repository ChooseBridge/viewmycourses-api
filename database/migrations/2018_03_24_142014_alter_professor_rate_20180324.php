<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfessorRate20180324 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('professor_rate', function (Blueprint $table) {
            $table->float('written_homework_num')->after('homework_num')->comment("书面作业量");
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
        Schema::table('professor_rate', function (Blueprint $table) {
            $table->dropColumn('written_homework_num');
        });
    }
}
