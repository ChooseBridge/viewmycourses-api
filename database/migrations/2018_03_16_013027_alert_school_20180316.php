<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertSchool20180316 extends Migration
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
            $table->integer('create_student_id')->default(0);
            $table->integer('create_user_id')->default(0);
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
        Schema::table('school', function (Blueprint $table) {
            $table->dropColumn('create_student_id');
            $table->dropColumn('create_user_id');
        });
    }
}
