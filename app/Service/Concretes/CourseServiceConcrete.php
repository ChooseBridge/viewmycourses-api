<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/6/6
 * Time: 9:26
 */

namespace App\Service\Concretes;

use App\ProfessorCourse;
use App\Service\Abstracts\CourseServiceAbstract;
use Illuminate\Support\Facades\Validator;


class CourseServiceConcrete implements CourseServiceAbstract
{

    public function getCourseForPage($limit=10)
    {
        $courses = ProfessorCourse::paginate($limit);
        return $courses;
    }

    public function validatorForCreate($data)
    {
        $validator = Validator::make($data, [
          'professor_id' => 'required|integer',
          'course_code' => 'required|max:255',
        ]);
        return $validator->fails() ? $validator : true;
    }

    public function createCourse($data)
    {
        $course= ProfessorCourse::create($data);
        return $course;
    }

    public function getCourseById($id)
    {
        $course= ProfessorCourse::where('course_id',$id)->first();
        return $course;
    }


}