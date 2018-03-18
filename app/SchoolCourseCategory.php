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

}
