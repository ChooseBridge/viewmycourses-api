<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessorCourse20180318 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('professor_course', function (Blueprint $table) {


            $table->increments('course_id');
            $table->integer('professor_id');
            $table->string('course_code');
            $table->unique(['professor_id', 'course_code']);
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
        Schema::drop('professor_course');
    }
}
