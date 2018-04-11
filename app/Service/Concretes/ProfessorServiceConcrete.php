<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/16
 * Time: 20:37
 */

namespace App\Service\Concretes;


use App\Exceptions\APIException;
use App\Professor;
use App\Service\Abstracts\MessageServiceAbstract;
use App\Service\Abstracts\ProfessorCourseServiceAbstract;
use App\Service\Abstracts\ProfessorRateServiceAbstract;
use App\Service\Abstracts\ProfessorServiceAbstract;
use Illuminate\Support\Facades\Validator;

class ProfessorServiceConcrete implements ProfessorServiceAbstract
{

    protected $messageService;
    protected $professorRateService;
    protected $professorCourseService;

    public function __construct(
      MessageServiceAbstract $messageService,
      ProfessorRateServiceAbstract $professorRateService,
      ProfessorCourseServiceAbstract $professorCourseService
    ) {
        $this->messageService = $messageService;
        $this->professorRateService = $professorRateService;
        $this->professorCourseService = $professorCourseService;

    }

    public function getProfessorsForPage($limit = 10, $queryCallBack = null, $join = null)
    {
        if ($queryCallBack == null && $join == null) {
            $professors = Professor::paginate($limit);
        } elseif ($queryCallBack && $join) {
            $builder = Professor::where($queryCallBack);
            foreach ($join as $key => $value) {
                $builder->whereHas($key, $value);
            }
            $professors = $builder->paginate($limit);
        } elseif ($join) {
            $professors = Professor::with($join)->paginate($limit);
        } else {
            $professors = Professor::where($queryCallBack)->paginate($limit);
        }


        return $professors;
    }


    public function validatorForCreate($data)
    {
        $validator = Validator::make($data, [
          'professor_fisrt_name' => 'required|max:255',
          'professor_second_name' => 'required|max:255',
          'school_id' => 'required|integer',
          'college_id' => 'required|integer',
        ]);
        return $validator->fails() ? $validator : true;
    }

    public function createProfessor($data)
    {
        $professor = Professor::create($data);
        return $professor;
    }

    public function approveProfessorById($id)
    {
        $professor = $this->getProfessorById($id);
        if ($professor) {
            $professor->check_status = Professor::APPROVE_CHECK;
            $isApprove = $professor->save();
            if ($isApprove) {
                $content = "你创建的教授" . $professor->professor_full_name . "审核通过";
                $student_id = $professor->create_student_id;
                $messageContent = [
                  'message'=>$content,
                  'type'=>'success',
                  'info_type'=>'professor',
                  'id'=>$professor->professor_id,
                  'name'=>$professor->professor_full_name,
                ];
                $data = [
                  'message_content' => json_encode($messageContent),
                  'to_student_id' => $student_id
                ];
                $this->messageService->createMessage($data);
            }
        }
    }

    public function rejectProfessorById($id)
    {
        $professor = $this->getProfessorById($id);
        if ($professor) {

            \DB::beginTransaction();
            try{

                $isReject = $professor->delete();
                if (!$isReject) {
                    throw new APIException("数据库操作异常",APIException::OPERATION_EXCEPTION);
                }

                $isdelete = $this->professorRateService->deleteRatesByProfessorId($id);
                if (!$isdelete) {
                    throw new APIException("数据库操作异常",APIException::OPERATION_EXCEPTION);
                }

                $isdelete = $this->professorCourseService->deleteCoursesByProfessorId($id);
                if (!$isdelete) {
                    throw new APIException("数据库操作异常",APIException::OPERATION_EXCEPTION);
                }

                

                $content = "你创建的教授" . $professor->professor_full_name . "审核失败";
                $student_id = $professor->create_student_id;
                $messageContent = [
                  'message' => $content,
                  'type' => 'fail',
                  'info_type' => 'professor',
                  'id' => $professor->professor_id,
                  'name' => $professor->professor_full_name,
                ];
                $data = [
                  'message_content' => json_encode($messageContent),
                  'to_student_id' => $student_id
                ];
                $message = $this->messageService->createMessage($data);
                if (!$message) {

                    throw new APIException("数据库操作异常", APIException::OPERATION_EXCEPTION);

                }
                \DB::commit();


            }catch (APIException $exception){
                \DB::rollBack();
            }


        }
    }

    public function getProfessorById($id)
    {
        $professor = Professor::where('professor_id', $id)->first();
        return $professor;
    }

    public function getRandomProfessorBySchoolId($schoolId)
    {

        $cache = \Illuminate\Support\Facades\Cache::get($schoolId . "professor");
        if ($cache) {
            return unserialize($cache);
        }

        $professors = Professor::where('school_id', $schoolId)->get();

        $professor = null;
        if(!empty($professors->toArray())){
            $professor = $professors->random();
        }


        if ($professor) {
            $cacheValue = serialize($professor);
            \Illuminate\Support\Facades\Cache::set($schoolId . "professor",$cacheValue,3600*24);
        }

        return $professor;


    }

    public function thumbsUpProfessorById($id, $student)
    {
        $professor = $this->getProfessorById($id);
        if ($professor) {
            if ($professor->thumbs_up == "") {
                $professor->thumbs_up = "," . $student->student_id . ",";
                $num = 1;
            } else {

                $studentIds = explode(',', trim($professor->thumbs_up, ','));

                if (in_array($student->student_id, $studentIds)) {
                    //cancel thumbs up
                    $studentIds = array_flip($studentIds);
                    unset($studentIds[$student->student_id]);
                    if (count($studentIds) == 0) {
                        $professor->thumbs_up = "";
                    } else {
                        $professor->thumbs_up = "," . implode(',', array_keys($studentIds)) . ",";
                    }
                    $num = -1;
                } else {
                    array_push($studentIds, $student->student_id);
                    $professor->thumbs_up = "," . implode(',', $studentIds) . ",";
                    $num = 1;
                }

            }
            if ($professor->save()) {
                return ['res' => true, 'num' => $num];
            }
        }
        return ['res' => false, 'num' => 0];
    }
}