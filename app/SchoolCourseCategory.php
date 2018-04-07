<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolCourseCategory extends Model
{
    //
    protected $table = 'school_course_category';

    protected $primaryKey = 'course_category_id';

    protected $fillable = [
      'school_id',
      'course_category_name',
    ];

    public function school(){
        return $this->belongsTo('App\School','school_id','school_id');
    }

}
