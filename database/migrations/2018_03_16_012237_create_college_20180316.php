<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollege20180316 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('college', function (Blueprint $table) {
            $table->increments('college_id');
            $table->string('college_name');
            $table->integer('shcool_id');
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
        Schema::drop('college');
    }
}
