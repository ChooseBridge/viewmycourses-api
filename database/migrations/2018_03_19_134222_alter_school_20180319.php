<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSchool20180319 extends Migration
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
            $table->string('school_nick_name_two')->after('school_nick_name')->default("");
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
            $table->integer('school_nick_name_two')->default(0);
        });
    }
}
