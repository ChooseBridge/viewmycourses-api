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

    public function getRatesForPage($limit = 10,$queryCallBack=null)
    {
        if ($queryCallBack) {
            $rates = SchoolRate::where($queryCallBack)->paginate($limit);
        } else {
            $rates = SchoolRate::paginate($limit);
        }

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
          ->where('check_status', SchoolRate::APPROVE_CHECK)
          ->orderBy('school_rate_id', 'DESC')
          ->get();
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

                $isset = $this->studentService->setPoints(Student::RATE_GET_POINT, '点评学校', $rate->student);
                if (!$isset) {
                    throw new  APIException("操作异常 设置积分失败", APIException::OPERATION_EXCEPTION);
                }

                $content = "您点评的学校" . $rate->school->school_name . "审核成功，添加了" . Student::RATE_GET_POINT . "积分";
                $student_id = $rate->create_student_id;
                $messageContent = [
                  'message'=>$content,
                  'type'=>'success',
                  'info_type'=>'school_rate',
                  'id'=>$rate->school_rate_id,
                  'name'=>$rate->school->school_name,
                  'school_id'=>$rate->school->school_id,
                ];
                $data = [
                  'message_content' => json_encode($messageContent),
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
            if ($isReject) {
                $content = "您点评的学校" . $rate->school->school_name . "审核失败";
                $student_id = $rate->create_student_id;
                $messageContent = [
                  'message'=>$content,
                  'type'=>'fail',
                  'info_type'=>'school_rate',
                  'id'=>$rate->school_rate_id,
                  'name'=>$rate->school->school_name,
                  'school_id'=>$rate->school->school_id,
                ];
                $data = [
                  'message_content' => json_encode($messageContent),
                  'to_student_id' => $student_id
                ];
                $this->messageService->createMessage($data);
            }
        }
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

}