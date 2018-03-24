<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfessorRate extends Model
{
    //

    const PENDING_CHECK = 0;
    const APPROVE_CHECK = 1;
//    const REJECT_CHECK = 2;

    public static $checkStatusName = [
      self::PENDING_CHECK => '等待审核',
      self::APPROVE_CHECK => '审核通过',
    ];

    protected $table = 'professor_rate';

    protected $primaryKey = 'professor_rate_id';

    protected $fillable = [
      'professor_id',
      'school_id',
      'college_id',
      'course_id',
      'course_code',
      'course_name',
      'course_category_id',
      'course_category_name',
      'is_attend',
      'difficult_level',
      'homework_num',
      'written_homework_num',
      'quiz_num',
      'course_related_quiz',
      'spend_course_time_at_week',
      'grade',
      'comment',
      'tag',
      'create_student_id',
      'check_status',
      'thumbs_up',
      'thumbs_down',
    ];


    protected $appends = ['check_status_name,attend,effort'];

    public function getEffortAttribute()
    {
        return $this->difficult_level*$this->spend_course_time_at_week*$this->quiz_num*(5/$this->course_related_quiz);
    }

    public function getCheckStatusNameAttribute()
    {
        $value = $this->check_status;

        return isset(self::$checkStatusName[$value]) ? self::$checkStatusName[$value] : "";
    }

    public function getAttendAttribute()
    {

        return $this->is_attend == 1 ? "出勤" : "缺勤";
    }

    public function professor()
    {
        return $this->belongsTo('App\Professor', 'professor_id', 'professor_id');
    }

    public function school()
    {
        return $this->belongsTo('App\School', 'school_id', 'school_id');
    }

    public function student()
    {
        return $this->belongsTo('App\Student', 'create_student_id', 'student_id');
    }

}
