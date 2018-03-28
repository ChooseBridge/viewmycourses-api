<?php

namespace App\Http\Controllers;

use App\Service\Abstracts\CityServiceAbstract;
use App\Service\Abstracts\CountryServiceAbstract;
use App\Service\Abstracts\ProvinceServiceAbstract;
use App\Service\Abstracts\SchoolServiceAbstract;
use Illuminate\Http\Request;

class CityController extends Controller
{
    //
    protected $cityService;
    protected $countryService;
    protected $provinceService;
    protected $schoolService;

    public function __construct(
      CityServiceAbstract $cityService,
      CountryServiceAbstract $countryService,
      ProvinceServiceAbstract $provinceService,
      SchoolServiceAbstract $schoolService
    ) {
        $this->cityService = $cityService;
        $this->countryService = $countryService;
        $this->provinceService = $provinceService;
        $this->schoolService = $schoolService;

    }


    public function index()
    {

        $citys = $this->cityService->getCitysForPage();
        return view('city.index', [
          'citys' => $citys
        ]);
    }

    public function addCity(Request $request)
    {
        if ($request->isMethod('POST')) {

            $data = $request->all();
            $validator = $this->cityService->validatorForCreate($data);
            if ($validator !== true) {
                return redirect(route('backend.city.add.get'))
                  ->withErrors($validator);
            }
            $this->cityService->createCity($data);
            return redirect(route("backend.city.index"));

        }

        $countrys = $this->countryService->getAllCountrys();
        return view('city.add', [
          'countrys' => $countrys
        ]);
    }


    public function updateCity(Request $request)
    {
        $cityId = $request->get('city_id');
        $city = $this->cityService->getCityById($cityId);
        if (!$city || !$cityId) {
            return redirect(route("backend.city.index"));
        }
        if ($request->isMethod('POST')) {
            $data = $request->all();
            $city->update($data);
            return redirect(route("backend.city.index"));
        }

        $countrys = $this->countryService->getAllCountrys();
        $provinces = $this->provinceService->getProvincesByCountryId($city->country_id);
        return view('city.update', [
          'countrys' => $countrys,
          'provinces' => $provinces,
          'city' => $city,
        ]);
    }

    public function deleteCity(Request $request)
    {

        $cityId = $request->get('city_id');
        $city = $this->cityService->getCityById($cityId);
        if (!$city || !$cityId) {
            $data = [
              'success' => false,
              'message' => '未知的城市'
            ];
            return json_encode($data);
        }
        $schools = $this->schoolService->getSchoolsByCityId($cityId);
        if (!empty($schools->toArray())) {
            $data = [
              'success' => false,
              'message' => '该城市下绑定了学校 无法删除'
            ];
            return json_encode($data);
        }
        if($city->delete()){
            $data = [
              'success' => true,
              'message' => '删除成功'
            ];
            return json_encode($data);
        }else{
            $data = [
              'success' => false,
              'message' => '删除失败'
            ];
            return json_encode($data);
        }


    }


}
