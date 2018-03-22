<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/18
 * Time: 12:50
 */

namespace App\Service\Concretes;


use App\SchoolCourseCategory;
use App\Service\Abstracts\SchoolCourseCategoryServiceAbstract;
use Illuminate\Support\Facades\Validator;

class SchoolCourseCategoryServiceConcrete implements SchoolCourseCategoryServiceAbstract
{


    public function createCourseCategory($data)
    {
        $schoolCourseCategory = SchoolCourseCategory::create($data);
        return $schoolCourseCategory;
    }

    public function validatorForCreate($data)
    {
        $validator = Validator::make($data, [
          'school_id' => 'required|integer',
          'course_category_name' => 'required|string',
        ]);
        return $validator->fails() ? $validator : true;
    }

    public function schoolHasCourseCategory($schoolId, $courseCategory)
    {
        $schoolCourseCategory = SchoolCourseCategory::where('school_id',$schoolId)
          ->where('course_category_name',$courseCategory)
          ->first();
        return !empty($schoolCourseCategory)?true:false;

    }

    public function getCourseCategorysBySchoolId($schoolId)
    {
        $schoolCourseCategorys = SchoolCourseCategory::where('school_id',$schoolId)
          ->get();
        return $schoolCourseCategorys;
    }


}