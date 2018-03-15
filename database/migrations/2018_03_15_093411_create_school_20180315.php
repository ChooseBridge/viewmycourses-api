<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchool20180315 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('school', function (Blueprint $table) {
            $table->increments('school_id');
            $table->string('school_name');
            $table->string('school_nick_name');
            $table->integer('country_id');
            $table->integer('province_id');
            $table->integer('city_id');
            $table->string('website_url');
            $table->string('your_email');
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
        Schema::drop('school');
    }
}
