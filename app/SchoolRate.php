<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolRate extends Model
{
    //

    protected $table = 'school_rate';

    protected $primaryKey = 'school_rate_id';

    protected $fillable = [
      'school_district_id',
      'social_reputation',
      'academic_level',
      'network_services',
      'accommodation',
      'food_quality',
      'campus_location',
      'extracurricular_activities',
      'campus_infrastructure',
      'life_happiness_index',
      'school_students_relations',
      'comment',
      'create_student_id',

    ];
}
