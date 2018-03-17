<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/17
 * Time: 15:36
 */

namespace App\Service\Concretes;


use App\SchoolRate;
use App\Service\Abstracts\SchoolRateServiceAbstract;
use Illuminate\Support\Facades\Validator;

class SchoolRateServiceConcrete implements SchoolRateServiceAbstract
{

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
}