<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStudent20180324 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('student', function (Blueprint $table) {
            $table->integer('gender')->nullable()->after('ucenter_uid')->comment("0: male, 1: female, 2: unknown");
            $table->integer('education_status')->nullable()->after('gender')->comment("0: high school, 1: university");
            $table->integer('is_graduate')->nullable()->after('education_status')->comment("0: 在读, 1: 毕业");
            $table->string('graduate_year')->nullable()->after('is_graduate');
            $table->string('school_name')->nullable()->after('graduate_year');
            $table->string('major')->nullable()->after('school_name');
            $table->string('exam_province')->nullable()->after('major');
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
        Schema::table('student', function (Blueprint $table) {
            $table->dropColumn('gender');
            $table->dropColumn('education_status');
            $table->dropColumn('is_graduate');
            $table->dropColumn('graduate_year');
            $table->dropColumn('school_name');
            $table->dropColumn('major');
            $table->dropColumn('exam_province');
        });
    }
}
