<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStudent20180321 extends Migration
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
            $table->string('access_token', 2000)->change();
            $table->string('refresh_token', 2000)->change();
            $table->smallInteger('is_vip')->after('password')->default(0);
            $table->dateTime('vip_expire_time')->after('password')->default('1970-01-01 00:00:00');
            $table->string('mobile')->after('password')->default("");
            $table->smallInteger('mobile_verified')->after('password');
            $table->smallInteger('email_verified')->after('password');
            $table->smallInteger('is_email_edu')->after('password');
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
            $table->string('access_token', 191)->change();
            $table->string('refresh_token', 191)->change();
            $table->dropColumn('is_vip');
            $table->dropColumn('vip_expire_time');
            $table->dropColumn('mobile');
            $table->dropColumn('mobile_verified');
            $table->dropColumn('email_verified');
            $table->dropColumn('is_email_edu');


        });
    }
}
