<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //

    const RATE_GET_POINT = 500;

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
      'mobile',
      'mobile_verified',
      'email_verified',
      'is_email_edu',
    ];
}
