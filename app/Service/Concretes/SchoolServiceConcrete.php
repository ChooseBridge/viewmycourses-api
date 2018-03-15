<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/15
 * Time: 21:19
 */

namespace App\Service\Concretes;


use App\School;
use App\Service\Abstracts\SchoolServiceAbstract;
use Illuminate\Support\Facades\Validator;

class SchoolServiceConcrete implements SchoolServiceAbstract
{

    public function createSchool($data)
    {
        $data['check_status'] = School::PENDING_CHECK;
        $school = School::create($data);
        return $school;
    }

    public function validatorForCreate($data)
    {
        $validator = Validator::make($data, [
          'school_name' => 'required|unique:school|max:255',
          'school_nick_name' => 'required|max:255',
          'country_id' => 'required|integer',
          'province_id' => 'required|integer',
          'city_id' => 'required|integer',
          'website_url' => 'required|max:255',
          'your_email' => 'required|email',
        ]);
        return $validator->fails()?$validator:true;

    }
}