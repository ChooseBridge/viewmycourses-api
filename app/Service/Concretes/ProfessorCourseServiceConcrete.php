<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/18
 * Time: 12:48
 */

namespace App\Service\Concretes;


use App\ProfessorCourse;
use App\Service\Abstracts\ProfessorCourseServiceAbstract;
use Illuminate\Support\Facades\Validator;

class ProfessorCourseServiceConcrete implements ProfessorCourseServiceAbstract
{
    public function createCourse($data)
    {
        $professorCourse = ProfessorCourse::create($data);
        return $professorCourse;
    }

    public function validatorForCreate($data)
    {
        $validator = Validator::make($data, [
          'professor_id' => 'required|integer',
          'course_code' => 'required|string',
        ]);
        return $validator->fails() ? $validator : true;
    }

    public function professorHasCourse($professorId, $courseCode)
    {
        $professorCourse = ProfessorCourse::where('professor_id',$professorId)
          ->where('course_code',$courseCode)
          ->first();
        return !empty($professorCourse)?true:false;
    }
}