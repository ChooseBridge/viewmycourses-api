<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolDistrict20180316 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('school_district', function (Blueprint $table) {
            $table->increments('school_district_id');
            $table->string('school_district_name');
            $table->integer('school_id');
            $table->integer('create_student_id')->default(0);
            $table->integer('create_user_id')->default(0);
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
        Schema::drop('school_district');
    }
}
