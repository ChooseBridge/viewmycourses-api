<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    //
    const PENDING_CHECK = 0;
    const APPROVE_CHECK = 1;
//    const REJECT_CHECK = 2;

    public static $checkStatusName = [
      self::PENDING_CHECK => '等待审核',
      self::APPROVE_CHECK => '审核通过',
    ];

    protected $table = 'school';

    protected $primaryKey = 'school_id';

    protected $fillable = [
      'school_name',
      'school_nick_name',
      'school_nick_name_two',
      'country_id',
      'province_id',
      'city_id',
      'website_url',
      'your_email',
      'check_status',
      'create_user_id',
      'create_student_id',
      'thumbs_up',
    ];

    protected $appends = ['check_status_name'];

    public function getCheckStatusNameAttribute()
    {
        $value = $this->check_status;

        return isset(self::$checkStatusName[$value]) ? self::$checkStatusName[$value] : "";
    }

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id', 'country_id');
    }

    public function province()
    {
        return $this->belongsTo('App\Province', 'province_id', 'province_id');
    }

    public function city()
    {
        return $this->belongsTo('App\City', 'city_id', 'city_id');
    }

    public function student()
    {
        return $this->belongsTo('App\Student', 'create_student_id', 'student_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'create_user_id', 'id');
    }

//    public function city()
//    {
//        return $this->belongsTo('App\City', 'city_id', 'city_id');
//    }


}
