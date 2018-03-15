<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\Service\Abstracts\ProvinceServiceAbstract;
use Illuminate\Http\Request;

class GeoController extends Controller
{
    protected $provinceService;

    public function __construct(ProvinceServiceAbstract $provinceService)
    {
        $this->provinceService = $provinceService;
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
          'data' =>$tmp
        ];

        return \Response::json($data);


    }

}
