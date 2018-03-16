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

    public function getProfessorsForPage($limit = 10)
    {
        $professors = Professor::paginate($limit);
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
}