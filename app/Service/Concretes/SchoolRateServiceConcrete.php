<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/17
 * Time: 15:36
 */

namespace App\Service\Concretes;


use App\Exceptions\APIException;
use App\SchoolRate;
use App\Service\Abstracts\MessageServiceAbstract;
use App\Service\Abstracts\SchoolRateServiceAbstract;
use App\Service\Abstracts\StudentServiceAbstract;
use App\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SchoolRateServiceConcrete implements SchoolRateServiceAbstract
{
    protected $studentService;

    public function __construct(
      StudentServiceAbstract $studentService,
      MessageServiceAbstract $messageService
    ) {
        $this->studentService = $studentService;
        $this->messageService = $messageService;
    }

    public function validatorForCreate($data)
    {
        $validator = Validator::make($data, [
          'school_district_id' => 'required|integer',
          'social_reputation' => 'required|numeric',
          'academic_level' => 'required|numeric',
          'network_services' => 'required|numeric',
          'accommodation' => 'required|numeric',
          'food_quality' => 'required|numeric',
          'campus_location' => 'required|numeric',
          'extracurricular_activities' => 'required|numeric',
          'campus_infrastructure' => 'required|numeric',
          'life_happiness_index' => 'required|numeric',
          'school_students_relations' => 'required|numeric',
          'comment' => 'required|string',
          'create_student_id' => 'required|integer',
        ]);
        return $validator->fails() ? $validator : true;
    }

    public function createRate($data)
    {
        $rate = SchoolRate::create($data);
        return $rate;
    }

    public function getRatesForPage($limit = 10)
    {
        $rates = SchoolRate::paginate($limit);
        return $rates;
    }

    public function getRatesBySchoolId($schoolId)
    {
        $rates = SchoolRate::where('school_id', $schoolId)->get();
        return $rates;
    }

    public function getCheckedRatesBySchoolId($schoolId)
    {
        $rates = SchoolRate::where('school_id', $schoolId)
          ->where('check_status', SchoolRate::APPROVE_CHECK)->get();
        return $rates;
    }

    public function getRatesByStudentId($studentId, $orderBy = ['school_rate_id', 'desc'])
    {
        $rates = SchoolRate::where('create_student_id', $studentId)
          ->orderBy($orderBy[0], $orderBy[1])
          ->get();
        return $rates;
    }

    public function getRateById($id)
    {
        $rate = SchoolRate::where('school_rate_id', $id)->first();
        return $rate;
    }

    public function approveRateById($id)
    {
        $rate = $this->getRateById($id);
        if ($rate) {
            $rate->check_status = SchoolRate::APPROVE_CHECK;
            try {
                \DB::beginTransaction();

                $isApprove = $rate->save();
                if (!$isApprove) {
                    throw new  APIException("操作异常 审核失败", APIException::OPERATION_EXCEPTION);
                }
                //待处理添加积分
                $isset = $this->studentService->setPoints(Student::RATE_GET_POINT, '点评学校', $rate->student);
                if (!$isset) {
                    throw new  APIException("操作异常 设置积分失败", APIException::OPERATION_EXCEPTION);
                }

                $content = "你点评的学校" . $rate->school->school_name . "审核成功，添加了" . Student::RATE_GET_POINT . "积分";
                $student_id = $rate->create_student_id;
                $data = [
                  'message_content' => $content,
                  'to_student_id' => $student_id
                ];
                $this->messageService->createMessage($data);

                DB::commit();

            } catch (APIException $exception) {
                \DB::rollBack();
                throw new $exception;
            }


        }
    }

    public function rejectRateById($id)
    {
        $rate = $this->getRateById($id);
        if ($rate) {
            $isReject = $rate->delete();
            if($isReject){
                $content = "你点评的学校" . $rate->school->school_name . "审核失败";
                $student_id = $rate->create_student_id;
                $data = [
                  'message_content' => $content,
                  'to_student_id' => $student_id
                ];
                $this->messageService->createMessage($data);
            }
        }
    }

}