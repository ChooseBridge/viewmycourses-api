<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\School;
use App\Service\Abstracts\CityServiceAbstract;
use App\Service\Abstracts\CountryServiceAbstract;
use App\Service\Abstracts\ProvinceServiceAbstract;
use App\Service\Abstracts\SchoolServiceAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

// backend
    public function index()
    {

        $schools = $this->schoolService->getSchoolsForPage();
        return view('school.index', [
          'schools' => $schools
        ]);
    }

    public function addSchool(Request $request)
    {
        if($request->isMethod('POST')){

            $data = $request->all();
            $data['your_email'] = Auth::user()->email;
            $data['create_user_id'] = Auth::user()->id;
            $data['check_status'] = School::APPROVE_CHECK;
            $validator = $this->schoolService->validatorForCreate($data);
            if($validator !== true){
                return redirect(route('backend.school.add.get'))
                  ->withErrors($validator);
            }
            $this->schoolService->createSchool($data);
            return redirect(route("backend.school.index"));


        }

        $countrys = $this->countryService->getAllCountrys();
        return view('school.add', [
          'countrys' => $countrys
        ]);
    }

    public function approve(Request $request){

        $school_id = $request->get('school_id');
        $this->schoolService->approveSchoolById($school_id);
        return redirect(route('backend.school.index'));
    }

    public function reject(Request $request){

        $school_id = $request->get('school_id');
        $this->schoolService->rejectSchoolById($school_id);
        return redirect(route('backend.school.index'));
    }


//  api
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
        $data['create_student_id'] = $GLOBALS['gStudent']->student_id;
        $data['check_status'] = School::PENDING_CHECK;
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
