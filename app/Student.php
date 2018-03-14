<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //

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
      'ucenter_uid'
    ];
}
