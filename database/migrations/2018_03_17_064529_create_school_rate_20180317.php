<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolRate20180317 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('school_rate', function (Blueprint $table) {
            $table->increments('school_rate_id');
            $table->integer('school_district_id')->comment('校区id');
            $table->float('social_reputation')->comment('社会声誉');
            $table->float('academic_level')->comment('学术水平');
            $table->float('network_services')->comment('网络服务');
            $table->float('accommodation')->comment('住宿条件');
            $table->float('food_quality')->comment('餐饮质量');
            $table->float('campus_location')->comment('校园地理位置');
            $table->float('extracurricular_activities')->comment('校园课外活动');
            $table->float('campus_infrastructure')->comment('校园基础设施');
            $table->float('life_happiness_index')->comment('生活幸福指数');
            $table->float('school_students_relations')->comment('校方与学生群体关系');
            $table->text('comment')->comment('文字点评');
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
        Schema::drop('school_rate');
    }
}
