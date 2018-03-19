<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/16
 * Time: 20:37
 */

namespace App\Service\Concretes;


use App\Professor;
use App\Service\Abstracts\ProfessorServiceAbstract;
use Illuminate\Support\Facades\Validator;

class ProfessorServiceConcrete implements ProfessorServiceAbstract
{

    public function getProfessorsForPage($limit = 10,$queryCallBack=null)
    {
        if($queryCallBack){
            $professors = Professor::where($queryCallBack)->paginate($limit);
        }else{
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
        if($professor){
            $professor->check_status = Professor::APPROVE_CHECK;
            $professor->save();
        }
    }

    public function rejectProfessorById($id)
    {
        $professor = $this->getProfessorById($id);
        if($professor){
            $professor->delete();
            //待处理是删除相关的教授点评等操作
        }
    }

    public function getProfessorById($id)
    {
        $professor = Professor::where('professor_id',$id)->first();
        return $professor;
    }
}