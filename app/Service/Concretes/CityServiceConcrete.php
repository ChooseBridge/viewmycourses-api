<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/15
 * Time: 15:30
 */

namespace App\Service\Concretes;


use App\City;
use App\Service\Abstracts\CityServiceAbstract;
use Illuminate\Support\Facades\Validator;

class CityServiceConcrete implements CityServiceAbstract
{
    public function getCitysForPage($limit = 10)
    {

        $citys = City::paginate($limit);
        return $citys;
    }

    public function getCitysByProvinceId($provinceId)
    {
        $citys = City::where('province_id', $provinceId)->get();
        return $citys;
    }

    public function getCityById($id)
    {
        $city = City::where('city_id', $id)->first();
        return $city;
    }

    public function validatorForCreate($data)
    {
        $validator = Validator::make($data, [
          'city_name' => 'required|max:255',
          'province_id' => 'required|integer',
          'country_id' => 'required|integer',
        ]);
        return $validator->fails() ? $validator : true;
    }

    public function createCity($data)
    {
        $city = City::create($data);
        return $city;
    }

}