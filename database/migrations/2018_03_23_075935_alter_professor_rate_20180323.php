<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfessorRate20180323 extends Migration
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
            $table->string('thumbs_up')->default("");
            $table->string('thumbs_down')->default("");
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
            $table->dropColumn('thumbs_up');
            $table->dropColumn('thumbs_down');
        });
    }
}
