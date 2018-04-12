<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfessorComment extends Model
{
    //

    protected $table = 'professor_comment';

    protected $primaryKey = 'comment_id';

    protected $fillable = [
      'comment',
      'create_student_id',
      'professor_id',
    ];


    public function student()
    {
        return $this->belongsTo('App\Student', 'create_student_id', 'student_id');
    }


    public function professor()
    {
        return $this->belongsTo('App\Professor', 'professor_id', 'professor_id');
    }

}
