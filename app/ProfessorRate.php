<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfessorRate extends Model
{
    //

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
      'quiz_num',
      'course_related_quiz',
      'spend_course_time_at_week',
      'grade',
      'comment',
      'tag',
      'create_student_id',
    ];

    protected $appends = ['attend'];

    public function getAttendAttribute()
    {

        return $this->is_attend == 1 ? "出勤" : "缺勤";
    }

    public function professor()
    {
        return $this->belongsTo('App\Professor', 'professor_id', 'professor_id');
    }

    public function student()
    {
        return $this->belongsTo('App\Student', 'create_student_id', 'student_id');
    }

}
