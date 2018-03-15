<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\Service\Abstracts\CityServiceAbstract;
use App\Service\Abstracts\CountryServiceAbstract;
use App\Service\Abstracts\ProvinceServiceAbstract;
use App\Service\Abstracts\SchoolServiceAbstract;
use Illuminate\Http\Request;

class SchoolController extends Controller
{


    protected $schoolService;
    protected $countryService;
    protected $provinceService;
    protected $cityService;

    public function __construct(
      SchoolServiceAbstract $schoolService,
      CountryServiceAbstract $countryService,
      ProvinceServiceAbstract $provinceService,
      CityServiceAbstract $cityService
    ) {
        $this->schoolService = $schoolService;
        $this->countryService = $countryService;
        $this->provinceService = $provinceService;
        $this->cityService = $cityService;
    }

    public function createSchool(Request $request)
    {
        $data = $request->all();

        $validator = $this->schoolService->validatorForCreate($data);
        if ($validator !== true) {
            $message = $validator->errors()->first();
            throw new APIException($message, APIException::ERROR_PARAM);
        }

        $country = $this->countryService->getCountryById($data['country_id']);
        $province = $this->provinceService->getProvinceById($data['province_id']);
        $city = $this->cityService->getCityById($data['city_id']);
        if (!$country || !$province || !$city) {
            throw new APIException('未找到 国家 或 省份 或 城市', APIException::ILLGAL_OPERATION);
        }

        $shcool = $this->schoolService->createSchool($data);
        if (!$shcool) {
            throw new APIException('操作异常', APIException::OPERATION_EXCEPTION);
        }

        $data = [
          'success' => true,
          'data' => '创建成功'
        ];

        return \Response::json($data);
    }
}
