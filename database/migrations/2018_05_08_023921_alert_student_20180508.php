<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertStudent20180508 extends Migration
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

            $table->smallInteger('is_assigned')->default(0)->after('is_vip')->comment("0 没有分配过6个月权限 1 已经分配过一次6个月权限");

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
            $table->dropColumn('is_assigned');
        });
    }
}
