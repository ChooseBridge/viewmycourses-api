<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\Service\Abstracts\CityServiceAbstract;
use App\Service\Abstracts\CountryServiceAbstract;
use App\Service\Abstracts\ProvinceServiceAbstract;
use Illuminate\Http\Request;

class GeoController extends Controller
{
    protected $provinceService;
    protected $countryService;
    protected $cityService;

    public function __construct(
      ProvinceServiceAbstract $provinceService,
      CountryServiceAbstract $countryService,
      CityServiceAbstract $cityService
    ) {
        $this->provinceService = $provinceService;
        $this->countryService = $countryService;
        $this->cityService = $cityService;
    }

    public function getProvinceByCountry(Request $request)
    {

        $countryId = $request->get('country_id');
        if (!$countryId) {
            throw new APIException('缺失参数', APIException::MISS_PARAM);
        }
        $provinces = $this->provinceService->getProvincesByCountryId($countryId);
        $tmp = [];
        foreach ($provinces as $province) {
            $tmp[] = [
              'province_id' => $province->province_id,
              'province_name' => $province->province_name,
            ];
        }

        $data = [
          'success' => true,
          'data' => $tmp
        ];

        return \Response::json($data);

    }

    public function getAllCountrys()
    {
        $countrys = $this->countryService->getAllCountrys();
        $tmp = [];
        foreach ($countrys as $country) {
            $tmp[] = [
              'country_id' => $country->country_id,
              'country_name' => $country->country_name,
            ];
        }

        $data = [
          'success' => true,
          'data' => $tmp
        ];

        return \Response::json($data);
    }

    public function getCityByProvince(Request $request)
    {
        $provinceId = $request->get('province_id');
        if (!$provinceId) {
            throw new APIException('缺失参数', APIException::MISS_PARAM);
        }
        $citys = $this->cityService->getCitysByProvinceId($provinceId);
        $tmp = [];
        foreach ($citys as $city) {
            $tmp[] = [
              'city_id' => $city->city_id,
              'city_name' => $city->city_name,
            ];
        }

        $data = [
          'success' => true,
          'data' => $tmp
        ];

        return \Response::json($data);
    }

}
