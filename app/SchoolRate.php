<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolRate extends Model
{
    //
    const PENDING_CHECK = 0;
    const APPROVE_CHECK = 1;
//    const REJECT_CHECK = 2;

    public static $checkStatusName = [
      self::PENDING_CHECK => '等待审核',
      self::APPROVE_CHECK => '审核通过',
    ];

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
      'check_status',

    ];

    protected $appends = ['check_status_name'];

    public function getCheckStatusNameAttribute()
    {
        $value = $this->check_status;

        return isset(self::$checkStatusName[$value]) ? self::$checkStatusName[$value] : "";
    }


    public function schoolDistrict()
    {
        return $this->belongsTo('App\SchoolDistrict', 'school_district_id', 'school_district_id');
    }

    public function student()
    {
        return $this->belongsTo('App\Student', 'create_student_id', 'student_id');
    }
}
