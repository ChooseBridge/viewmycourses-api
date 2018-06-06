<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfessorCourse extends Model
{
    //
    protected $table = 'professor_course';

    protected $primaryKey = 'course_id';

    protected $fillable = [
      'professor_id',
      'course_code',
    ];

    public function professor()
    {
        return $this->belongsTo('App\Professor', 'professor_id', 'professor_id');
    }

}
