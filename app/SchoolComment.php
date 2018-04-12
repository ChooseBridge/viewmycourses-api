<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolComment extends Model
{
    //
    protected $table = 'school_comment';

    protected $primaryKey = 'comment_id';

    protected $fillable = [
      'comment',
      'create_student_id',
      'school_id',
    ];

    public function student()
    {
        return $this->belongsTo('App\Student', 'create_student_id', 'student_id');
    }


    public function school()
    {
        return $this->belongsTo('App\School', 'school_id', 'school_id');
    }
}
