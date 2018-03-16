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
        return $validator->fails() ? $validator : true;

    }

    public function approveSchoolById($id)
    {
        $school = $this->getSchoolById($id);
        if($school){
            $school->check_status = School::APPROVE_CHECK;
            $school->save();
        }
    }

    public function rejectSchoolById($id)
    {
        $school = $this->getSchoolById($id);
        if($school){
            $school->delete();
        }
    }

    public function getSchoolsForPage($limit = 10)
    {
        $schools = School::paginate($limit);
        return $schools;
    }

    public function getSchoolById($id)
    {
        $school = School::where('school_id', $id)->first();
        return $school;
    }
}