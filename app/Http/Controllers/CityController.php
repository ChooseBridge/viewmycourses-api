<?php

namespace App\Http\Controllers;

use App\Service\Abstracts\CityServiceAbstract;
use App\Service\Abstracts\CountryServiceAbstract;
use Illuminate\Http\Request;

class CityController extends Controller
{
    //
    protected $cityService;

    public function __construct(CityServiceAbstract $cityService)
    {
        $this->cityService = $cityService;
    }


    public function index(){

        $citys = $this->cityService->getCitysForPage();
        return view('city.index',[
          'citys'=>$citys
        ]);
    }

    public function addCity(CountryServiceAbstract $countryService,Request $request)
    {
        if($request->isMethod('POST')){

            $data = $request->all();
            $validator = $this->cityService->validatorForCreate($data);
            if($validator !== true){
                return redirect(route('backend.city.add.get'))
                  ->withErrors($validator);
            }
            $this->cityService->createCity($data);
            return redirect(route("backend.city.index"));

        }

        $countrys = $countryService->getAllCountrys();
        return view('city.add',[
          'countrys'=>$countrys
        ]);
    }



}
