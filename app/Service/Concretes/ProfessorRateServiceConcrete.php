<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/17
 * Time: 15:33
 */

namespace App\Service\Concretes;


use App\ProfessorRate;
use App\Service\Abstracts\ProfessorRateServiceAbstract;
use Illuminate\Support\Facades\Validator;

class ProfessorRateServiceConcrete implements ProfessorRateServiceAbstract
{

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

    public function getRatesForPage($limit=10)
    {
        $rates = ProfessorRate::paginate($limit);
        return $rates;
    }
}