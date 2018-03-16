<?php

namespace App\Http\Controllers;

use App\Service\Abstracts\CountryServiceAbstract;
use App\Service\Abstracts\ProvinceServiceAbstract;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    //
    protected $provinceService;
    protected $countryService;

    public function __construct(
      ProvinceServiceAbstract $provinceService,
      CountryServiceAbstract $countryService
    ) {
        $this->provinceService = $provinceService;
        $this->countryService = $countryService;
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
}
