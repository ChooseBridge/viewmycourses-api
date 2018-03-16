<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    //

    protected $table = 'college';

    protected $primaryKey = 'college_id';

    protected $fillable = [
      'college_name',
      'school_id',
      'create_student_id',
      'create_user_id',
    ];

    public function school()
    {
        return $this->belongsTo('App\School','school_id','school_id');
    }

    public function student()
    {
        return $this->belongsTo('App\Student', 'create_student_id', 'student_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'create_user_id', 'id');
    }
}
