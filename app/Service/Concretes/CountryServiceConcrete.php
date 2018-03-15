<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/15
 * Time: 13:02
 */

namespace App\Service\Concretes;


use App\Country;
use App\Service\Abstracts\CountryServiceAbstract;
use Illuminate\Support\Facades\Validator;

class CountryServiceConcrete implements CountryServiceAbstract
{

    public function getAllCountrys()
    {
        $countrys = Country::all();
        return $countrys;
    }

    public function getCountrysForPage($limit = 10)
    {
        $countrys = Country::paginate($limit);
        return $countrys;
    }

    public function getCountryById($id)
    {
        $country = Country::where('country_id', $id)->first();
        return $country;
    }

    public function validatorForCreate($data)
    {
        $validator = Validator::make($data, [
          'country_name' => 'required|unique:country|max:255',
        ]);
        return $validator->fails() ? $validator : true;
    }

    public function createCountry($data)
    {
        $country = Country::create($data);
        return $country;
    }

}