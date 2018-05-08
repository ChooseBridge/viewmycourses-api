<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //

    const RATE_GET_POINT = 100;

    protected $table = 'student';

    protected $primaryKey = 'student_id';

    protected $fillable = [
      'name',
      'email',
      'password',
      'token',
      'token_expires_time',
      'access_token',
      'refresh_token',
      'access_token_expires_time',
      'ucenter_uid',
      'is_vip',
      'vip_expire_time',
      'is_assigned',
      'mobile',
      'mobile_verified',
      'email_verified',
      'is_email_edu',
      'gender',
      'education_status',
      'is_graduate',
      'graduate_year',
      'school_name',
      'major',
      'exam_province',
    ];
}
