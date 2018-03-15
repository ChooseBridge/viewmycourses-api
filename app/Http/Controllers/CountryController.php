<?php

namespace App\Http\Controllers;

use App\Service\Abstracts\CountryServiceAbstract;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;

class CountryController extends Controller
{
    //
    protected $countryService;

    public function __construct(CountryServiceAbstract $countryService)
    {
        $this->countryService = $countryService;
    }


    public function index(){

        $countrys = $this->countryService->getCountrysForPage();
        return view('country.index',[
          'countrys'=>$countrys
        ]);
    }

    public function addCountry(Request $request){

        if($request->isMethod('POST')){

            $data = $request->all();
            $validator = $this->countryService->validatorForCreate($data);
            if($validator !== true){
                return redirect(route('backend.country.add.get'))
                  ->withErrors($validator);
            }
            $this->countryService->createCountry($data);
            return redirect(route("backend.country.index"));


        }
        return view('country.add');

    }


}
