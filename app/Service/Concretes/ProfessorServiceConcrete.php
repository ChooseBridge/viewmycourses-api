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

    public function getProfessorsForPage($limit = 10, $queryCallBack = null)
    {
        if ($queryCallBack) {
            $professors = Professor::where($queryCallBack)->paginate($limit);
        } else {
            $professors = Professor::paginate($limit);
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
}