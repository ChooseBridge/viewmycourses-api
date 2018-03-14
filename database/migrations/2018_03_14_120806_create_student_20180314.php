<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudent20180314 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student', function (Blueprint $table) {
            $table->increments('student_id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('token');
            $table->dateTime('token_expires_time');
            $table->string('access_token');
            $table->string('refresh_token');
            $table->dateTime('access_token_expires_time');
            $table->integer('ucenter_uid')->default(0);
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

        Schema::drop('student');
    }
}
