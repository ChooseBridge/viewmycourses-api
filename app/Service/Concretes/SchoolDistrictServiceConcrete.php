<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/16
 * Time: 16:51
 */

namespace App\Service\Concretes;


use App\SchoolDistrict;
use App\Service\Abstracts\SchoolDistrictServiceAbstract;
use Illuminate\Support\Facades\Validator;

class SchoolDistrictServiceConcrete implements SchoolDistrictServiceAbstract
{

    public function getDistrictsForPage($limit = 10)
    {
        $districts = SchoolDistrict::paginate($limit);
        return $districts;
    }

    public function validatorForCreate($data)
    {
        $validator = Validator::make($data, [
          'school_district_name' => 'required|max:255',
          'school_id' => 'required|integer',
        ]);
        return $validator->fails() ? $validator : true;
    }

    public function createDistrict($data)
    {
        $district = SchoolDistrict::create($data);
        return $district;
    }

    public function getDistrictById($id)
    {
        $district = SchoolDistrict::where('school_district_id',$id)->first();
        return $district;
    }

    public function getDistrictsBySchoolId($schoolId)
    {
        $districts = SchoolDistrict::where('school_id',$schoolId)->get();
        return $districts;
    }

}