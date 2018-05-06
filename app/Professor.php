<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    //

    const PENDING_CHECK = 0;
    const APPROVE_CHECK = 1;
//    const REJECT_CHECK = 2;

    public static $checkStatusName = [
      self::PENDING_CHECK => '等待审核',
      self::APPROVE_CHECK => '审核通过',
    ];

    protected $table = 'professor';

    protected $primaryKey = 'professor_id';

    protected $fillable = [
      'professor_fisrt_name',
      'professor_second_name',
      'professor_full_name',
      'professor_web_site',
      'school_id',
      'college_id',
      'create_student_id',
      'create_user_id',
      'check_status',
      'thumbs_up',
      'p_sort',
    ];


    protected $appends = ['check_status_name'];

    public function getCheckStatusNameAttribute()
    {
        $value = $this->check_status;

        return isset(self::$checkStatusName[$value]) ? self::$checkStatusName[$value] : "";
    }

    public function school()
    {
        return $this->belongsTo('App\School','school_id','school_id');
    }

    public function college()
    {
        return $this->belongsTo('App\College','college_id','college_id');
    }
    public function student()
    {
        return $this->belongsTo('App\Student', 'create_student_id', 'student_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'create_user_id', 'id');
    }

    public function comments(){
        return $this->hasMany('App\ProfessorComment', 'professor_id', 'professor_id');
    }


}
