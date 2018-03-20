<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/17
 * Time: 15:33
 */

namespace App\Service\Concretes;


use App\ProfessorRate;
use App\SchoolCourseCategory;
use App\Service\Abstracts\ProfessorCourseServiceAbstract;
use App\Service\Abstracts\ProfessorRateServiceAbstract;
use App\Service\Abstracts\SchoolCourseCategoryServiceAbstract;
use App\Service\Abstracts\StudentServiceAbstract;
use App\Student;
use Illuminate\Support\Facades\Validator;

class ProfessorRateServiceConcrete implements ProfessorRateServiceAbstract
{

    protected $professorCourseService;
    protected $schoolCourseCategoryService;
    protected $studentService;

    public function __construct(
      ProfessorCourseServiceAbstract $professorCourseService,
      SchoolCourseCategoryServiceAbstract $schoolCourseCategoryService,
      StudentServiceAbstract $studentService
    ) {
        $this->professorCourseService = $professorCourseService;
        $this->schoolCourseCategoryService = $schoolCourseCategoryService;
        $this->studentService = $studentService;
    }

    public function validatorForCreate($data)
    {
        $validator = Validator::make($data, [
          'professor_id' => 'required|integer',
          'school_id' => 'required|integer',
          'college_id' => 'required|integer',
          'course_code' => 'required|max:255',
          'course_name' => 'required|max:255',
          'course_category_name' => 'required|max:255',
          'is_attend' => 'required|integer',
          'difficult_level' => 'required|numeric',
          'homework_num' => 'required|numeric',
          'quiz_num' => 'required|integer',
          'course_related_quiz' => 'required|numeric',
          'spend_course_time_at_week' => 'required|integer',
          'grade' => 'required|max:255',
          'comment' => 'required|string',
          'tag' => 'required|max:255',
          'create_student_id' => 'required|integer',
        ]);
        return $validator->fails() ? $validator : true;
    }

    public function createRate($data)
    {
        $rate = ProfessorRate::create($data);
        return $rate;
    }

    public function getRatesForPage($limit = 10)
    {
        $rates = ProfessorRate::paginate($limit);
        return $rates;
    }

    public function getRateById($id)
    {
        $rate = ProfessorRate::where('professor_rate_id', $id)->first();
        return $rate;
    }

    public function getRatesByProfessorId($professorId)
    {
        $rates = ProfessorRate::where('professor_id', $professorId)->get();
        return $rates;
    }

    public function getCheckedRatesByProfessorId($professorId)
    {
        $rates = ProfessorRate::where('professor_id', $professorId)
          ->where('check_status',ProfessorRate::APPROVE_CHECK)
          ->get();
        return $rates;
    }

    public function approveRateById($id)
    {
        $rate = $this->getRateById($id);
        if ($rate) {
            $rate->check_status = ProfessorRate::APPROVE_CHECK;
            //待处理添加课程和课程类别 和添加积分

            $this->studentService->setPoints(Student::RATE_GET_POINT);

            //代表用户手动填写course_code
            if ($rate->course_id == 0) {
                $hasCourse = $this->professorCourseService->professorHasCourse($rate->professor_id, $rate->course_code);
                if (!$hasCourse) {
                    $data = [
                      'professor_id' => $rate->professor_id,
                      'course_code' => $rate->course_code,
                    ];
                    if ($this->professorCourseService->validatorForCreate($data)) {
                        $course = $this->professorCourseService->createCourse($data);
                        $rate->course_id = $course->course_id;
                    }

                }
            }

            //代表用户手动填写course_category_name
            if ($rate->course_category_id == 0) {
                $hasCourseCategory = $this->schoolCourseCategoryService->schoolHasCourseCategory($rate->school_id,
                  $rate->course_category_name);
                if (!$hasCourseCategory) {
                    $data = [
                      'school_id' => $rate->school_id,
                      'course_category_name' => $rate->course_category_name,
                    ];
                    if ($this->schoolCourseCategoryService->validatorForCreate($data)) {
                        $category = $this->schoolCourseCategoryService->createCourseCategory($data);
                        $rate->course_category_id = $category->course_category_id;
                    }
                }
            }

            $rate->save();

        }
    }

    public function rejectRateById($id)
    {
        $rate = $this->getRateById($id);
        if ($rate) {
            $rate->delete();
        }
    }
}