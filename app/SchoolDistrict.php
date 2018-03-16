<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolDistrict extends Model
{
    //
    protected $table = 'school_district';

    protected $primaryKey = 'school_district_id';

    protected $fillable = [
      'school_district_name',
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
