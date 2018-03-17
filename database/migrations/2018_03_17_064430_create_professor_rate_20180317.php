<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessorRate20180317 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('professor_rate', function (Blueprint $table) {
            $table->increments('professor_rate_id');
            $table->integer('professor_id');
            $table->integer('school_id');
            $table->integer('college_id');
            $table->integer('course_id')->default(0);
            $table->string('course_code');
            $table->string('course_name');
            $table->integer('course_category_id')->default(0);
            $table->string('course_category_name');
            $table->integer('is_attend')->comment('是否出勤');
            $table->float('difficult_level')->comment('课程难度');
            $table->float('homework_num')->comment('笔头作业量');
            $table->integer('quiz_num')->comment('每月考试数');
            $table->float('course_related_quiz')->comment('课程与考试内容相关度');
            $table->integer('spend_course_time_at_week')->comment('每周课堂外所花总时间');
            $table->string('grade')->comment('你的成绩');
            $table->text('comment')->comment('文字点评');
            $table->string('tag')->comment('标签');
            $table->integer('create_student_id');
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
        Schema::drop('professor_rate');
    }
}
