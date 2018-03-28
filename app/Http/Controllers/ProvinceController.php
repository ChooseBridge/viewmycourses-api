<?php

namespace App\Http\Controllers;

use App\Service\Abstracts\CityServiceAbstract;
use App\Service\Abstracts\CountryServiceAbstract;
use App\Service\Abstracts\ProvinceServiceAbstract;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    //
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

    public function index()
    {

        $provinces = $this->provinceService->getProvincesForPage();
        return view('province.index', [
          'provinces' => $provinces
        ]);
    }

    public function addProvince(Request $request)
    {
        if ($request->isMethod('POST')) {

            $data = $request->all();
            $validator = $this->provinceService->validatorForCreate($data);
            if ($validator !== true) {
                return redirect(route('backend.province.add.get'))
                  ->withErrors($validator);
            }
            $this->provinceService->createProvince($data);
            return redirect(route("backend.province.index"));
        }

        $countrys = $this->countryService->getAllCountrys();
        return view('province.add', [
          'countrys' => $countrys
        ]);
    }

    public function updateProvince(Request $request)
    {

        $provinceId = $request->get('province_id');
        $province = $this->provinceService->getProvinceById($provinceId);
        if (!$provinceId || !$province) {
            return redirect(route("backend.province.index"));
        }
        if ($request->isMethod('POST')) {
            $data = $request->all();
            $province->update($data);
            return redirect(route("backend.province.index"));
        }

        $countrys = $this->countryService->getAllCountrys();
        return view('province.update', [
          'province' => $province,
          'countrys' => $countrys
        ]);


    }

    public function deleteProvince(Request $request)
    {
        $provinceId = $request->get('province_id');
        $province = $this->provinceService->getProvinceById($provinceId);
        if (!$provinceId || !$province) {
            $data = [
              'success' => false,
              'message' => '未知的省份'
            ];
            return json_encode($data);
        }
        $citys = $this->cityService->getCitysByProvinceId($province->province_id);
        if (!empty($citys->toArray())) {
            $data = [
              'success' => false,
              'message' => '该省份下绑定了城市 不可以删除 请先删除城市'
            ];
            return json_encode($data);
        }
        if($province->delete()){
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
