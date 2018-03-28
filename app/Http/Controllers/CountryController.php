<?php

namespace App\Http\Controllers;

use App\Service\Abstracts\CountryServiceAbstract;
use App\Service\Abstracts\ProvinceServiceAbstract;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;

class CountryController extends Controller
{
    //
    protected $countryService;
    protected $provinceService;

    public function __construct(
      CountryServiceAbstract $countryService,
      ProvinceServiceAbstract $provinceService
    ) {
        $this->countryService = $countryService;
        $this->provinceService = $provinceService;
    }


    public function index()
    {

        $countrys = $this->countryService->getCountrysForPage();
        return view('country.index', [
          'countrys' => $countrys
        ]);
    }

    public function addCountry(Request $request)
    {

        if ($request->isMethod('POST')) {

            $data = $request->all();
            $validator = $this->countryService->validatorForCreate($data);
            if ($validator !== true) {
                return redirect(route('backend.country.add.get'))
                  ->withErrors($validator);
            }
            $this->countryService->createCountry($data);
            return redirect(route("backend.country.index"));


        }
        return view('country.add');

    }


    public function updateCountry(Request $request)
    {

        $countryId = $request->get('country_id');
        $country = $this->countryService->getCountryById($countryId);
        if (!$countryId || !$country) {
            return redirect(route("backend.country.index"));
        }
        if ($request->isMethod('POST')) {
            $data = $request->all();
            $country->update($data);
            return redirect(route("backend.country.index"));
        }
        return view('country.update',[
          'country'=>$country
        ]);
    }

    public function deleteCountry(Request $request)
    {
        $countryId = $request->get('country_id');
        $country = $this->countryService->getCountryById($countryId);
        if (!$countryId || !$country) {
            $data = [
              'success' => false,
              'message' => '未知的国家'
            ];
            return json_encode($data);
        }
        $provinces = $this->provinceService->getProvincesByCountryId($country->country_id);
        if (!empty($provinces->toArray())) {
            $data = [
              'success' => false,
              'message' => '国家下还有关联的省份 不能删除  请先删除省份'
            ];
            return json_encode($data);
        }
        if ($country->delete()) {
            $data = [
              'success' => true,
              'message' => '删除成功'
            ];
            return json_encode($data);
        } else {
            $data = [
              'success' => false,
              'message' => '删除失败'
            ];
            return json_encode($data);
        }

    }


}
