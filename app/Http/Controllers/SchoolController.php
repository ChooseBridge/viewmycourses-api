<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\School;
use App\SchoolComment;
use App\Service\Abstracts\CityServiceAbstract;
use App\Service\Abstracts\CountryServiceAbstract;
use App\Service\Abstracts\ProfessorRateServiceAbstract;
use App\Service\Abstracts\ProfessorServiceAbstract;
use App\Service\Abstracts\ProvinceServiceAbstract;
use App\Service\Abstracts\SchoolDistrictServiceAbstract;
use App\Service\Abstracts\SchoolRateServiceAbstract;
use App\Service\Abstracts\SchoolServiceAbstract;
use App\Service\Abstracts\StudentServiceAbstract;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Overtrue\Pinyin\Pinyin;

class SchoolController extends Controller
{


    protected $schoolService;
    protected $countryService;
    protected $provinceService;
    protected $cityService;
    protected $schoolRateService;
    protected $professorRateService;
    protected $schoolDistrictService;
    protected $professorService;
    protected $studentService;

    public function __construct(
      SchoolServiceAbstract $schoolService,
      CountryServiceAbstract $countryService,
      ProvinceServiceAbstract $provinceService,
      CityServiceAbstract $cityService,
      SchoolRateServiceAbstract $schoolRateService,
      ProfessorRateServiceAbstract $professorRateService,
      SchoolDistrictServiceAbstract $schoolDistrictService,
      ProfessorServiceAbstract $professorService,
      StudentServiceAbstract $studentService
    ) {
        $this->schoolService = $schoolService;
        $this->countryService = $countryService;
        $this->provinceService = $provinceService;
        $this->cityService = $cityService;
        $this->schoolRateService = $schoolRateService;
        $this->professorRateService = $professorRateService;
        $this->schoolDistrictService = $schoolDistrictService;
        $this->professorService = $professorService;
        $this->studentService = $studentService;
    }

// backend
    public function index(Request $request)
    {

        $check_status = $request->get('check_status');
        if($check_status != null){

            $callback = function ($query) use ($check_status){
                $query->where('check_status',$check_status);
            };
        }

        if(isset($callback)){
            $schools = $this->schoolService->getSchoolsForPage(10,$callback);
        }else{
            $schools = $this->schoolService->getSchoolsForPage();
        }

        return view('school.index', [
          'schools' => $schools,
          'check_status' => $check_status
        ]);
    }

    public function addSchool(Request $request)
    {
        if ($request->isMethod('POST')) {

            $data = $request->all();
            $data['your_email'] = Auth::user()->email;
            $data['create_user_id'] = Auth::user()->id;
            $data['check_status'] = School::APPROVE_CHECK;
            if ($data['school_nick_name_two'] == null) {
                $data['school_nick_name_two'] = "";
            }
            $validator = $this->schoolService->validatorForCreate($data);
            if ($validator !== true) {
                return redirect(route('backend.school.add.get'))
                  ->withErrors($validator);
            }
            $pinyin = new Pinyin();
            $data['p_sort'] = substr($pinyin->abbr($data['school_name']),0,1);
            $this->schoolService->createSchool($data);
            return redirect(route("backend.school.index"));


        }

        $countrys = $this->countryService->getAllCountrys();
        return view('school.add', [
          'countrys' => $countrys
        ]);
    }


    public function updateSchool(Request $request)
    {

        $schoolId = $request->get('school_id');
        $school = $this->schoolService->getSchoolById($schoolId);
        if (!$schoolId || !$school) {
            return redirect(route("backend.school.index"));
        }
        if ($request->isMethod('POST')) {

            $data = $request->all();
            $pinyin = new Pinyin();
            $data['p_sort'] = substr($pinyin->abbr($data['school_name']),0,1);
            $school->update($data);
            return redirect(route("backend.school.index"));

        }
        $countrys = $this->countryService->getAllCountrys();
        $provinces = $this->provinceService->getProvincesByCountryId($school->country_id);
        $citys = $this->cityService->getCitysByProvinceId($school->province_id);
        return view('school.update', [
          'countrys' => $countrys,
          'provinces' => $provinces,
          'citys' => $citys,
          'school' => $school,
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

    public function showComment(Request $request){
        $school_id = $request->get('school_id');
        $school = $this->schoolService->getSchoolById($school_id);
        return view('school.comment', [
          'school' => $school,
        ]);
    }

    public function commentIndex(){

        $comments = SchoolComment::paginate(10);
        return view('school.comment_index', [
          'comments' => $comments,
        ]);
    }


//  api
    public function createSchool(Request $request)
    {



//        if (!$this->studentService->currentStudentIsVip()) {
//            throw new APIException("此操作需要会员权限", APIException::IS_NOT_VIP);
//        }

        $data = $request->all();

        $user = $this->studentService->getCurrentStudent();

        $data['your_email'] = $user->email;

        $validator = $this->schoolService->validatorForCreate($data);
        if ($validator !== true) {
            $message = $validator->errors()->first();
            throw new APIException($message, APIException::ERROR_PARAM);
        }

        $pinyin = new Pinyin();
        $data['p_sort'] = substr($pinyin->abbr($data['school_name']),0,1);


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
          'data' => [
            'id' => $shcool->school_id
          ]
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


        $shcools = [];
        $schoolName = $request->get('school_name', null);
        if (!$schoolName) {
            throw  new APIException("缺失参数school_name", APIException::MISS_PARAM);
        }


        $queryCallBack = function ($query) use ($schoolName) {
            $query->where('school_name', 'like', "%$schoolName%");
            $query->orWhere('school_nick_name', 'like', "%$schoolName%");
            $query->orWhere('school_nick_name_two', 'like', "%$schoolName%");
        };
        $result = $this->schoolService->getAllCheckedSchools($queryCallBack);

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


        $shcools = [];
        $schoolName = $request->get('school_name', null);
        $countryId = $request->get('country_id', null);
        $provinceId = $request->get('province_id', null);
        $cityId = $request->get('city_id', null);
        $limit = $request->get('pageSize', 10);;

        $queryCallBack = function ($query) use ($schoolName, $countryId, $provinceId, $cityId) {

            if ($schoolName) {
                $query->where(function ($query) use ($schoolName) {
                    $query->where('school_name', 'like', "%$schoolName%");
                    $query->orWhere('school_nick_name', 'like', "%$schoolName%");
                    $query->orWhere('school_nick_name_two', 'like', "%$schoolName%");
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
        $result = $this->schoolService->getAllCheckedSchoolsForPage($limit, $queryCallBack);

        foreach ($result as $school) {
            $shcools[] = [
              'school_id' => $school->school_id,
              'school_name' => $school->school_name,
              'school_nick_name' => $school->school_nick_name,
              'country' => $school->country?$school->country->country_name:"",
              'province' => $school->province?$school->province->province_name:"",
              'city' => $school->city?$school->city->city_name:"",
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
        $tmp['last_page'] = $pageInfo['last_page'];
        $tmp['per_page'] = $pageInfo['per_page'];
        $pageInfo = $tmp;


        $data = [
          'success' => true,
          'data' => [
            'schools' => $shcools,
            'pageInfo' => $pageInfo,
          ]
        ];
        return \Response::json($data);
    }

    public function getSchoolDetail(Request $request)
    {


        $schoolId = $request->get('school_id', null);
        if (!$schoolId) {
            throw new APIException("参数school id缺失", APIException::MISS_PARAM);
        }

        $school = $this->schoolService->getSchoolById($schoolId);
        if (!$school) {
            throw new APIException("未知的学校", APIException::DATA_EXCEPTION);
        }


        $randomProfessor = $this->professorService->getRandomProfessorBySchoolId($schoolId);
        if ($randomProfessor) {
            $randomProfessorEffort = $this->professorRateService->getEffortByProfessorId($randomProfessor->professor_id);
            $randomProfessorRates = $this->professorRateService->getRatesByProfessorId($randomProfessor->professor_id);
            $randomProfessorRatesNum = $randomProfessorRates->count();


            $tmp = [
              'professor_id' => $randomProfessor->professor_id,
              'professor_full_name' => $randomProfessor->professor_full_name,
              'professor_web_site' => $randomProfessor->professor_web_site,
              'school_name' => $randomProfessor->school->school_name,
              'college_name' =>$randomProfessor->college_id == 0 ?$randomProfessor->college_name : $randomProfessor->college->college_name,
              'effort' => round($randomProfessorEffort, 1),
              'rate_num' => $randomProfessorRatesNum
            ];
            $randomProfessor = $tmp;
        }


        $effort = $this->professorRateService->getEffortBySchoolId($schoolId);

        $rates = $this->schoolRateService->getCheckedRatesBySchoolId($schoolId);
        $ratesInfo = [];

        $allScore = [];
        $schoolDistrictScore = [];

        $max_thumbs_up_rate_id = 0;
        $max_thumbs_up = 0;
        foreach ($rates as $rate) {

            if (!isset($allScore['score'])) {
                $allScore['score'] = $rate->score;
                $allScore['num'] = 1;
            } else {
                $allScore['score'] += $rate->score;
                $allScore['num'] += 1;
            }

            if (!isset($schoolDistrictScore[$rate->school_district_id]['score'])) {
                $schoolDistrictScore[$rate->school_district_id]['score'] = $rate->score;
                $schoolDistrictScore[$rate->school_district_id]['social_reputation'] = $rate->social_reputation;
                $schoolDistrictScore[$rate->school_district_id]['academic_level'] = $rate->academic_level;
                $schoolDistrictScore[$rate->school_district_id]['network_services'] = $rate->network_services;
                $schoolDistrictScore[$rate->school_district_id]['accommodation'] = $rate->accommodation;
                $schoolDistrictScore[$rate->school_district_id]['food_quality'] = $rate->food_quality;
                $schoolDistrictScore[$rate->school_district_id]['campus_location'] = $rate->campus_location;
                $schoolDistrictScore[$rate->school_district_id]['extracurricular_activities'] = $rate->extracurricular_activities;
                $schoolDistrictScore[$rate->school_district_id]['life_happiness_index'] = $rate->life_happiness_index;
                $schoolDistrictScore[$rate->school_district_id]['school_students_relations'] = $rate->school_students_relations;
                $schoolDistrictScore[$rate->school_district_id]['campus_infrastructure'] = $rate->campus_infrastructure;
                $schoolDistrictScore[$rate->school_district_id]['num'] = 1;
            } else {
                $schoolDistrictScore[$rate->school_district_id]['score'] += $rate->score;
                $schoolDistrictScore[$rate->school_district_id]['social_reputation'] += $rate->social_reputation;
                $schoolDistrictScore[$rate->school_district_id]['academic_level'] += $rate->academic_level;
                $schoolDistrictScore[$rate->school_district_id]['network_services'] += $rate->network_services;
                $schoolDistrictScore[$rate->school_district_id]['accommodation'] += $rate->accommodation;
                $schoolDistrictScore[$rate->school_district_id]['food_quality'] += $rate->food_quality;
                $schoolDistrictScore[$rate->school_district_id]['campus_location'] += $rate->campus_location;
                $schoolDistrictScore[$rate->school_district_id]['extracurricular_activities'] += $rate->extracurricular_activities;
                $schoolDistrictScore[$rate->school_district_id]['life_happiness_index'] += $rate->life_happiness_index;
                $schoolDistrictScore[$rate->school_district_id]['school_students_relations'] += $rate->school_students_relations;
                $schoolDistrictScore[$rate->school_district_id]['campus_infrastructure'] += $rate->campus_infrastructure;
                $schoolDistrictScore[$rate->school_district_id]['num'] += 1;
            }

            $tmp = [
              'school_rate_id' => $rate->school_rate_id,
              'school_district_name' => $rate->schoolDistrict->school_district_name,
              'social_reputation' => $rate->social_reputation,
              'academic_level' => $rate->academic_level,
              'network_services' => $rate->network_services,
              'accommodation' => $rate->accommodation,
              'food_quality' => $rate->food_quality,
              'campus_location' => $rate->campus_location,
              'extracurricular_activities' => $rate->extracurricular_activities,
              'campus_infrastructure' => $rate->campus_infrastructure,
              'life_happiness_index' => $rate->life_happiness_index,
              'school_students_relations' => $rate->school_students_relations,
              'comment' => $rate->comment,
              'student_name' => $rate->student ? $rate->student->name : "",
              'major' => $rate->student ? $rate->student->major : "",
              'score' => round($rate->score, 1),
              'created_at' => $rate->created_at->format('Y-m-d H:i:s'),
              'create_student_id' => $rate->create_student_id,
            ];




            //将点赞数最多的一条点评置顶，若点赞数相同，则置顶最新的点评
            if ($rate->thumbs_up != "") {
                $thumbsUpNum = count(explode(',', trim($rate->thumbs_up, ',')));
                if ($thumbsUpNum > $max_thumbs_up) {
                    $max_thumbs_up_rate_id = $rate->school_rate_id;
                    $max_thumbs_up = $thumbsUpNum;
                }
            }

            //计算百分比
            if ($rate->thumbs_up == "" && $rate->thumbs_down == "") {
                $tmp['thumbs_up_percent'] = 0;
                $tmp['thumbs_down_percent'] = 0;
            } elseif ($rate->thumbs_up == "") {
                $tmp['thumbs_up_percent'] = 0;
                $tmp['thumbs_down_percent'] = 100;
            } elseif ($rate->thumbs_down == "") {
                $tmp['thumbs_up_percent'] = 100;
                $tmp['thumbs_down_percent'] = 0;
            } else {
                $thumbsUpNum = count(explode(',', trim($rate->thumbs_up, ',')));
                $thumbsUpDown = count(explode(',', trim($rate->thumbs_down, ',')));
                $total = $thumbsUpNum + $thumbsUpDown;
                $thumbsUpPercent = $thumbsUpNum * 100 / $total;
                $tmp['thumbs_up_percent'] = floor($thumbsUpPercent);
                $tmp['thumbs_down_percent'] = 100 - $tmp['thumbs_up_percent'];
            }

            //检查是否 点击有用 点击无用
            if ($this->studentService->getCurrentStudent()) {
                if (strpos($rate->thumbs_up, ",{$GLOBALS['gStudent']->student_id},") === false) {
                    $tmp["is_thumbs_up"] = false;
                } else {
                    $tmp["is_thumbs_up"] = true;
                }
                if (strpos($rate->thumbs_down, ",{$GLOBALS['gStudent']->student_id},") === false) {
                    $tmp["is_thumbs_down"] = false;
                } else {
                    $tmp["is_thumbs_down"] = true;
                }
            }

            $ratesInfo[$tmp['school_rate_id']] = $tmp;


        }

        if($max_thumbs_up_rate_id != 0){
            $max_rate = $ratesInfo[$max_thumbs_up_rate_id];
            unset($ratesInfo[$max_thumbs_up_rate_id]);
            array_unshift($ratesInfo,$max_rate);
        }

        $ratesInfo = array_values($ratesInfo);

        $schoolDistricts = $this->schoolDistrictService->getDistrictsBySchoolId($schoolId);
        $schoolDistrictInfo = [];

        foreach ($schoolDistricts as $schoolDistrict) {
            $score = 0;
            $socialReputation = 0;
            $academicLevel = 0;
            $networkServices = 0;
            $accommodation = 0;
            $foodQuality = 0;
            $campusLocation = 0;
            $extracurricularActivities = 0;
            $lifeHappinessIndex = 0;
            $schoolStudentsRelations = 0;
            $campusInfrastructure = 0;


            if (isset($schoolDistrictScore[$schoolDistrict->school_district_id]['score'])) {
                $score = $schoolDistrictScore[$schoolDistrict->school_district_id]['score'] / $schoolDistrictScore[$schoolDistrict->school_district_id]['num'];
            }
            if (isset($schoolDistrictScore[$schoolDistrict->school_district_id]['social_reputation'])) {
                $socialReputation = $schoolDistrictScore[$schoolDistrict->school_district_id]['social_reputation'] / $schoolDistrictScore[$schoolDistrict->school_district_id]['num'];
            }
            if (isset($schoolDistrictScore[$schoolDistrict->school_district_id]['academic_level'])) {
                $academicLevel = $schoolDistrictScore[$schoolDistrict->school_district_id]['academic_level'] / $schoolDistrictScore[$schoolDistrict->school_district_id]['num'];
            }
            if (isset($schoolDistrictScore[$schoolDistrict->school_district_id]['network_services'])) {
                $networkServices = $schoolDistrictScore[$schoolDistrict->school_district_id]['network_services'] / $schoolDistrictScore[$schoolDistrict->school_district_id]['num'];
            }
            if (isset($schoolDistrictScore[$schoolDistrict->school_district_id]['accommodation'])) {
                $accommodation = $schoolDistrictScore[$schoolDistrict->school_district_id]['accommodation'] / $schoolDistrictScore[$schoolDistrict->school_district_id]['num'];
            }
            if (isset($schoolDistrictScore[$schoolDistrict->school_district_id]['food_quality'])) {
                $foodQuality = $schoolDistrictScore[$schoolDistrict->school_district_id]['food_quality'] / $schoolDistrictScore[$schoolDistrict->school_district_id]['num'];
            }
            if (isset($schoolDistrictScore[$schoolDistrict->school_district_id]['campus_location'])) {
                $campusLocation = $schoolDistrictScore[$schoolDistrict->school_district_id]['campus_location'] / $schoolDistrictScore[$schoolDistrict->school_district_id]['num'];
            }
            if (isset($schoolDistrictScore[$schoolDistrict->school_district_id]['extracurricular_activities'])) {
                $extracurricularActivities = $schoolDistrictScore[$schoolDistrict->school_district_id]['extracurricular_activities'] / $schoolDistrictScore[$schoolDistrict->school_district_id]['num'];
            }
            if (isset($schoolDistrictScore[$schoolDistrict->school_district_id]['life_happiness_index'])) {
                $lifeHappinessIndex = $schoolDistrictScore[$schoolDistrict->school_district_id]['life_happiness_index'] / $schoolDistrictScore[$schoolDistrict->school_district_id]['num'];
            }
            if (isset($schoolDistrictScore[$schoolDistrict->school_district_id]['school_students_relations'])) {
                $schoolStudentsRelations = $schoolDistrictScore[$schoolDistrict->school_district_id]['school_students_relations'] / $schoolDistrictScore[$schoolDistrict->school_district_id]['num'];
            }
            if (isset($schoolDistrictScore[$schoolDistrict->school_district_id]['campus_infrastructure'])) {
                $campusInfrastructure = $schoolDistrictScore[$schoolDistrict->school_district_id]['campus_infrastructure'] / $schoolDistrictScore[$schoolDistrict->school_district_id]['num'];
            }


            $schoolDistrictInfo[] = [
              'school_district_id' => $schoolDistrict->school_district_id,
              'school_district_name' => $schoolDistrict->school_district_name,
              'social_reputation' => round($socialReputation, 1),
              'academic_level' => round($academicLevel, 1),
              'network_services' => round($networkServices, 1),
              'accommodation' => round($accommodation, 1),
              'food_quality' => round($foodQuality, 1),
              'campus_location' => round($campusLocation, 1),
              'extracurricular_activities' => round($extracurricularActivities, 1),
              'life_happiness_index' => round($lifeHappinessIndex, 1),
              'school_students_relations' => round($schoolStudentsRelations, 1),
              'school_district_score' => round($score, 1),
              'campus_infrastructure' => round($campusInfrastructure, 1),
            ];
        }


        $schoolScore = 0;
        if (isset($allScore['score'])) {
            $schoolScore = $allScore['score'] / $allScore['num'];
        }

        $schoolInfo = [
          'school_name' => $school->school_name,
          'country' => $school->country->country_name,
          'province' => $school->province->province_name,
          'city' => $school->city->city_name,
          'website_url' => $school->website_url,
          'effort' => round($effort, 1),
          'school_score' => round($schoolScore, 1),
        ];

        if ($school->thumbs_up == "") {
            $schoolInfo['thumbs_up_num'] = 0;
        } else {
            $schoolInfo['thumbs_up_num'] = count(explode(',', trim($school->thumbs_up, ',')));
        }

        if ($this->studentService->getCurrentStudent()) {
            if (strpos($school->thumbs_up, ",{$GLOBALS['gStudent']->student_id},") === false) {
                $schoolInfo["is_thumbs_up"] = false;
            } else {
                $schoolInfo["is_thumbs_up"] = true;
            }
        }


        $data = [
          'success' => true,
          'data' => [
            'randomProfessor' => $randomProfessor,
            'schoolInfo' => $schoolInfo,
            'schoolDistrictInfo' => $schoolDistrictInfo,
            'ratesInfo' => $ratesInfo,
          ]
        ];
        return \Response::json($data);
    }


    public function thumbsUpSchool(Request $request)
    {

        if (!$this->studentService->currentStudentIsVip()) {
            throw new APIException("此操作需要会员权限", APIException::IS_NOT_VIP);
        }

        $schoolId = $request->get('school_id');
        if (!$schoolId) {
            throw new APIException("miss param school id ", APIException::MISS_PARAM);
        }
        $student = $GLOBALS['gStudent'];
        $res = $this->schoolService->thumbsUpSchoolById($schoolId, $student);
        if ($res['res']) {
            $data = [
              'success' => true,
              'data' => [
                'msg' => 'thumbs up success',
                'num' => $res['num'],
              ]
            ];
        } else {
            $data = [
              'success' => false,
              'data' => [
                'msg' => 'thumbs up false',
              ]
            ];
        }
        return \Response::json($data);
    }


    public function createComment(Request $request)
    {


        $student = $this->studentService->getCurrentStudent();
        $schoolId = $request->get('school_id');
        $comment = $request->get('comment');
        if (!$schoolId || !$comment) {
            throw new APIException("miss param school_id or comment", APIException::MISS_PARAM);
        }
        SchoolComment::create([
          'school_id' => $schoolId,
          'comment' => $comment,
          'create_student_id' => $student->student_id,
        ]);
        $data = [
          'success' => true,
          'data' => 'comment success'
        ];
        return \Response::json($data);


    }
}
