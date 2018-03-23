<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/16
 * Time: 20:37
 */

namespace App\Service\Concretes;


use App\Professor;
use App\Service\Abstracts\MessageServiceAbstract;
use App\Service\Abstracts\ProfessorServiceAbstract;
use Illuminate\Support\Facades\Validator;

class ProfessorServiceConcrete implements ProfessorServiceAbstract
{

    protected $messageService;

    public function __construct(MessageServiceAbstract $messageService)
    {
        $this->messageService = $messageService;
    }

    public function getProfessorsForPage($limit = 10, $queryCallBack = null, $join = null)
    {
        if ($queryCallBack == null && $join == null) {
            $professors = Professor::paginate($limit);
        } elseif ($queryCallBack && $join) {
            $builder = Professor::where($queryCallBack);
            foreach ($join as $key =>$value){
                $builder ->whereHas($key,$value);
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
                $data = [
                  'message_content' => $content,
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
            $isReject = $professor->delete();
            if ($isReject) {
                //待处理是删除相关的教授点评等操作
                $content = "你创建的教授" . $professor->professor_full_name . "审核失败";
                $student_id = $professor->create_student_id;
                $data = [
                  'message_content' => $content,
                  'to_student_id' => $student_id
                ];
                $this->messageService->createMessage($data);
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