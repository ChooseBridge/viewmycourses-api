<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/16
 * Time: 11:34
 */

namespace App\Service\Concretes;


use App\College;
use App\Service\Abstracts\CollegeServiceAbstract;
use Illuminate\Support\Facades\Validator;

class CollegeServiceConcrete implements CollegeServiceAbstract
{
    public function getCollegesForPage($limit=10)
    {
        $colleges = College::paginate($limit);

        return $colleges;
    }

    public function validatorForCreate($data)
    {
        $validator = Validator::make($data, [
          'college_name' => 'required|max:255',
          'school_id' => 'required|integer',
        ]);
        return $validator->fails() ? $validator : true;
    }

    public function createCollege($data)
    {
        $college = College::create($data);
        return $college;
    }

    public function getCollegesBySchoolId($schoolId)
    {
        $colleges = College::where('school_id',$schoolId)->get();
        return $colleges;
    }
}