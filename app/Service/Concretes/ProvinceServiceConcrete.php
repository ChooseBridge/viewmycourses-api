<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/15
 * Time: 14:43
 */

namespace App\Service\Concretes;


use App\Province;
use App\Service\Abstracts\ProvinceServiceAbstract;
use Illuminate\Support\Facades\Validator;

class ProvinceServiceConcrete implements ProvinceServiceAbstract
{
    public function getAllProvinces(){
        $provinces =  Province::all();
        return $provinces;
    }

    public function getProvincesForPage($limit=10){
        $provinces = Province::paginate($limit);
        return $provinces;
    }

    public function getProvincesByCountryId($countryId){
        $provinces = Province::where('country_id',$countryId)->get();
        return $provinces;
    }

    public function validatorForCreate($data){
        $validator = Validator::make($data, [
          'province_name' => 'required|max:255',
          'country_id' => 'required|integer',
        ]);
        return $validator->fails()?$validator:true;
    }

    public function createProvince($data){
        $province = Province::create($data);
        return $province;
    }
}