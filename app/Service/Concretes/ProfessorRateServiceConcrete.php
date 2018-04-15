<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/17
 * Time: 15:33
 */

namespace App\Service\Concretes;


use App\Exceptions\APIException;
use App\ProfessorRate;
use App\SchoolCourseCategory;
use App\Service\Abstracts\MessageServiceAbstract;
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
    protected $messageService;

    public function __construct(
      ProfessorCourseServiceAbstract $professorCourseService,
      SchoolCourseCategoryServiceAbstract $schoolCourseCategoryService,
      StudentServiceAbstract $studentService,
      MessageServiceAbstract $messageService
    ) {
        $this->professorCourseService = $professorCourseService;
        $this->schoolCourseCategoryService = $schoolCourseCategoryService;
        $this->studentService = $studentService;
        $this->messageService = $messageService;
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

    public function getRatesForPage($limit = 10,$queryCallBack=null)
    {
        if ($queryCallBack) {
            $rates = ProfessorRate::where($queryCallBack)->paginate($limit);
        }else{
            $rates = ProfessorRate::paginate($limit);
        }

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

    public function getEffortBySchoolId($schoolId)
    {
        $rates = $this->getCheckedRatesBySchoolId($schoolId);
        $effort = 0;
        $calculateAllEffort = [];
        foreach ($rates as $rate) {
            if (!isset($calculateAllEffort['effort'])) {
                $calculateAllEffort['effort'] = $rate->effort;
                $calculateAllEffort['num'] = 1;
            } else {
                $calculateAllEffort['effort'] += $rate->effort;
                $calculateAllEffort['num'] += 1;
            }
        }
        if (isset($calculateAllEffort['effort'])) {
            $effort = $calculateAllEffort['effort'] / $calculateAllEffort['num'];
        }
        return $effort;
    }


    public function getEffortByProfessorId($professorId)
    {
        $rates = $this->getCheckedRatesByProfessorId($professorId);
        $effort = 0;
        $calculateAllEffort = [];
        foreach ($rates as $rate) {
            if (!isset($calculateAllEffort['effort'])) {
                $calculateAllEffort['effort'] = $rate->effort;
                $calculateAllEffort['num'] = 1;
            } else {
                $calculateAllEffort['effort'] += $rate->effort;
                $calculateAllEffort['num'] += 1;
            }
        }
        if (isset($calculateAllEffort['effort'])) {
            $effort = $calculateAllEffort['effort'] / $calculateAllEffort['num'];
        }
        return $effort;
    }

    public function getCheckedRatesBySchoolId($schoolId)
    {
        $rates = ProfessorRate::where('school_id', $schoolId)
          ->where('check_status', ProfessorRate::APPROVE_CHECK)
          ->get();
        return $rates;
    }

    public function getCheckedRatesByProfessorId($professorId)
    {
        $rates = ProfessorRate::where('professor_id', $professorId)
          ->where('check_status', ProfessorRate::APPROVE_CHECK)
          ->get();
        return $rates;
    }

    public function getRatesByStudentId($studentId, $orderBy = ["professor_rate_id", "desc"])
    {
        $rates = ProfessorRate::where('create_student_id', $studentId)
          ->orderBy($orderBy[0], $orderBy[1])
          ->get();
        return $rates;
    }

    public function approveRateById($id)
    {
        $rate = $this->getRateById($id);
        if ($rate) {
            $rate->check_status = ProfessorRate::APPROVE_CHECK;


            try {
                \DB::beginTransaction();

                //代表用户手动填写course_code
                if ($rate->course_id == 0) {
                    $hasCourse = $this->professorCourseService->professorHasCourse($rate->professor_id,
                      $rate->course_code);
                    if (!$hasCourse) {
                        $data = [
                          'professor_id' => $rate->professor_id,
                          'course_code' => $rate->course_code,
                        ];
                        if ($this->professorCourseService->validatorForCreate($data)) {
                            $course = $this->professorCourseService->createCourse($data);
                            if (!$course) {
                                throw new  APIException("操作异常 课程添加失败", APIException::OPERATION_EXCEPTION);
                            }
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
                            if (!$category) {
                                throw new  APIException("操作异常 课程类别添加失败", APIException::OPERATION_EXCEPTION);
                            }
                            $rate->course_category_id = $category->course_category_id;
                        }
                    }
                }

                $isApprove = $rate->save();
                if (!$isApprove) {
                    throw new  APIException("操作异常 审核失败", APIException::OPERATION_EXCEPTION);
                }

                $isset = $this->studentService->setPoints(Student::RATE_GET_POINT, '点评教授', $rate->student);
                if (!$isset) {
                    throw new  APIException("操作异常 设置积分失败", APIException::OPERATION_EXCEPTION);
                }

                $content = "您点评的教授" . $rate->professor->professor_full_name . "审核成功，添加了" . Student::RATE_GET_POINT . "积分";
                $student_id = $rate->create_student_id;
                $messageContent = [
                  'message'=>$content,
                  'type'=>'success',
                  'info_type'=>'professor_rate',
                  'id'=>$rate->professor_rate_id,
                  'name'=>$rate->professor->professor_full_name,
                ];
                $data = [
                  'message_content' => json_encode($messageContent),
                  'to_student_id' => $student_id
                ];
                $this->messageService->createMessage($data);

                \DB::commit();

            } catch (APIException $exception) {
                \DB::rollBack();
                throw $exception;
            }

        }
    }

    public function rejectRateById($id)
    {
        $rate = $this->getRateById($id);
        if ($rate) {
            $isReject = $rate->delete();
            if ($isReject) {
                $content = "您点评的教授" . $rate->professor->professor_full_name . "审核失败";
                $student_id = $rate->create_student_id;
                $messageContent = [
                  'message'=>$content,
                  'type'=>'fail',
                  'info_type'=>'professor_rate',
                  'id'=>$rate->professor_rate_id,
                  'name'=>$rate->professor->professor_full_name,
                ];
                $data = [
                  'message_content' => json_encode($messageContent),
                  'to_student_id' => $student_id
                ];
                $this->messageService->createMessage($data);
            }

        }
    }

    public function thumbsDownRateById($id, $student)
    {
        $rate = $this->getRateById($id);

        if (strpos($rate->thumbs_up, ",{$student->student_id},") !== false) {
            throw  new  APIException("已经点击过有用", APIException::OPERATION_EXCEPTION);
        }

        if ($rate) {
            if ($rate->thumbs_down == "") {
                $rate->thumbs_down = "," . $student->student_id . ",";
                $num = 1;
            } else {

                $studentIds = explode(',', trim($rate->thumbs_down, ','));

                if (in_array($student->student_id, $studentIds)) {
                    //cancel thumbs up
                    $studentIds = array_flip($studentIds);
                    unset($studentIds[$student->student_id]);
                    if (count($studentIds) == 0) {
                        $rate->thumbs_down = "";
                    } else {
                        $rate->thumbs_down = "," . implode(',', array_keys($studentIds)) . ",";
                    }
                    $num = -1;
                } else {
                    array_push($studentIds, $student->student_id);
                    $rate->thumbs_down = "," . implode(',', $studentIds) . ",";
                    $num = 1;
                }

            }
            if ($rate->save()) {
                return ['res' => true, 'num' => $num];
            }
        }
        return ['res' => false, 'num' => 0];
    }

    public function thumbsUpRateById($id, $student)
    {
        $rate = $this->getRateById($id);

        if (strpos($rate->thumbs_down, ",{$student->student_id},") !== false) {
            throw  new  APIException("已经点击过无用", APIException::OPERATION_EXCEPTION);
        }
        if ($rate) {
            if ($rate->thumbs_up == "") {
                $rate->thumbs_up = "," . $student->student_id . ",";
                $num = 1;
            } else {

                $studentIds = explode(',', trim($rate->thumbs_up, ','));

                if (in_array($student->student_id, $studentIds)) {
                    //cancel thumbs up
                    $studentIds = array_flip($studentIds);
                    unset($studentIds[$student->student_id]);
                    if (count($studentIds) == 0) {
                        $rate->thumbs_up = "";
                    } else {
                        $rate->thumbs_up = "," . implode(',', array_keys($studentIds)) . ",";
                    }
                    $num = -1;
                } else {
                    array_push($studentIds, $student->student_id);
                    $rate->thumbs_up = "," . implode(',', $studentIds) . ",";
                    $num = 1;
                }

            }
            if ($rate->save()) {
                return ['res' => true, 'num' => $num];
            }
        }
        return ['res' => false, 'num' => 0];
    }

    public function deleteRatesByProfessorId($professorId)
    {
        return ProfessorRate::where('professor_id',$professorId)->delete();
    }

}