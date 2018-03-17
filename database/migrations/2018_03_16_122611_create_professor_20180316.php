<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessor20180316 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professor', function (Blueprint $table) {
            $table->increments('professor_id');
            $table->string('professor_fisrt_name');
            $table->string('professor_second_name');
            $table->string('professor_full_name');
            $table->string('professor_web_site')->default("");
            $table->integer('school_id');
            $table->integer('college_id');
            $table->integer('create_student_id')->default(0);
            $table->integer('create_user_id')->default(0);
            $table->integer('check_status');
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
        //
        Schema::drop('professor');
    }
}
