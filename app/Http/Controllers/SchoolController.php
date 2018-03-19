<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\School;
use App\Service\Abstracts\CityServiceAbstract;
use App\Service\Abstracts\CountryServiceAbstract;
use App\Service\Abstracts\ProvinceServiceAbstract;
use App\Service\Abstracts\SchoolServiceAbstract;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        if ($request->isMethod('POST')) {

            $data = $request->all();
            $data['your_email'] = Auth::user()->email;
            $data['create_user_id'] = Auth::user()->id;
            $data['check_status'] = School::APPROVE_CHECK;
            $validator = $this->schoolService->validatorForCreate($data);
            if ($validator !== true) {
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

    public function approve(Request $request)
    {

        $school_id = $request->get('school_id');
        $this->schoolService->approveSchoolById($school_id);
        return redirect(route('backend.school.index'));
    }

    public function reject(Request $request)
    {

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

    public function getAllcheckedSchoolByCountry()
    {

        $shcools = $this->schoolService->getAllCheckedSchoolsGroupCountry();
        $data = [
          'success' => true,
          'data' => $shcools
        ];
        return \Response::json($data);

    }

    /*
     * 根据名称获取所有的学校不分页
     */
    public function getAllSchoolByName(Request $request)
    {

        //待处理搜索权限

        $shcools = [];
        $schoolName = $request->get('school_name', null);
        if (!$schoolName) {
            throw  new APIException("缺失参数school_name", APIException::MISS_PARAM);
        }


        $queryCallBack = function ($query) use ($schoolName) {
            $query->where('school_name', 'like', "%$schoolName%");
            $query->orWhere('school_nick_name', 'like', "%$schoolName%");
        };
        $result = $this->schoolService->getAllSchools($queryCallBack);

        foreach ($result as $school) {
            $shcools[] = [
              'school_id' => $school->school_id,
              'school_name' => $school->school_name,
              'school_nick_name' => $school->school_nick_name,
            ];
        }


        $data = [
          'success' => true,
          'data' => $shcools
        ];
        return \Response::json($data);
    }


    /*
     * 根据条件获取学校分页
     */
    public function getSchoolByCondition(Request $request)
    {

        //待处理搜索权限


        $shcools = [];
        $schoolName = $request->get('school_name', null);
        $countryId = $request->get('country_id', null);
        $provinceId = $request->get('province_id', null);
        $cityId = $request->get('city_id', null);

        $queryCallBack = function ($query) use ($schoolName, $countryId, $provinceId, $cityId) {

            if ($schoolName) {
                $query->where(function ($query) use ($schoolName) {
                    $query->where('school_name', 'like', "%$schoolName%");
                    $query->orWhere('school_nick_name', 'like', "%$schoolName%");
                });
            }
            if ($countryId) {
                $query->where('country_id', $countryId);
            }
            if ($provinceId) {
                $query->where('province_id', $provinceId);
            }
            if ($cityId) {
                $query->where('city_id', $cityId);
            }

        };
        $result = $this->schoolService->getSchoolsForPage(1, $queryCallBack);

        foreach ($result as $school) {
            $shcools[] = [
              'school_id' => $school->school_id,
              'school_name' => $school->school_name,
              'school_nick_name' => $school->school_nick_name,
            ];
        }

        $pageInfo = $result->appends([
          'school_name' => $schoolName,
          'country_id' => $countryId,
          'province_id' => $provinceId,
          'city_id' => $cityId,
        ])->toArray();
        $tmp = [];
        $tmp['first_page_url'] = $pageInfo['first_page_url'];
        $tmp['last_page_url'] = $pageInfo['last_page_url'];
        $tmp['prev_page_url'] = $pageInfo['prev_page_url'];
        $tmp['next_page_url'] = $pageInfo['next_page_url'];
        $tmp['last_page_url'] = $pageInfo['last_page_url'];
        $tmp['total'] = $pageInfo['total'];
        $pageInfo = $tmp;


        $data = [
          'success' => true,
          'data' => [
            'shcools' => $shcools,
            'pageInfo' => $pageInfo,
          ]
        ];
        return \Response::json($data);
    }
}
