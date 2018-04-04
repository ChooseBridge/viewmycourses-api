<?php

namespace App\Http\Controllers;

use App\Country;
use App\Exceptions\APIException;
use App\ProfessorRate;
use App\School;
use App\SchoolRate;
use App\Service\Abstracts\CityServiceAbstract;
use App\Service\Abstracts\CollegeServiceAbstract;
use App\Service\Abstracts\CountryServiceAbstract;
use App\Service\Abstracts\MessageServiceAbstract;
use App\Service\Abstracts\ProfessorRateServiceAbstract;
use App\Service\Abstracts\ProvinceServiceAbstract;
use App\Service\Abstracts\SchoolCourseCategoryServiceAbstract;
use App\Service\Abstracts\SchoolRateServiceAbstract;
use App\Service\Abstracts\SchoolServiceAbstract;
use App\Service\Abstracts\StudentServiceAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{

    protected $studentService;
    protected $professorRateService;
    protected $schoolRateService;
    protected $messageService;
    protected $schoolService;
    protected $collegeService;
    protected $countryService;
    protected $provinceService;
    protected $cityService;

    public function __construct(
      StudentServiceAbstract $studentService,
      ProfessorRateServiceAbstract $professorRateService,
      SchoolRateServiceAbstract $schoolRateService,
      MessageServiceAbstract $messageService,
      SchoolServiceAbstract $schoolService,
      CollegeServiceAbstract $collegeService,
      CountryServiceAbstract $countryService,
      ProvinceServiceAbstract $provinceService,
      CityServiceAbstract $cityService
    ) {
        $this->studentService = $studentService;
        $this->professorRateService = $professorRateService;
        $this->schoolRateService = $schoolRateService;
        $this->messageService = $messageService;
        $this->schoolService = $schoolService;
        $this->collegeService = $collegeService;
        $this->countryService = $countryService;
        $this->provinceService = $provinceService;
        $this->cityService = $cityService;
    }

//api
    public function getStudent()
    {

        $student = [
          'name' => $GLOBALS['gStudent']->name,
          'email' => $GLOBALS['gStudent']->email,
          'is_email_edu' => $GLOBALS['gStudent']->is_email_edu,
          'gender' => $GLOBALS['gStudent']->gender,
          'mobile' => $GLOBALS['gStudent']->mobile,
          'education_status' => $GLOBALS['gStudent']->education_status,
          'is_graduate' => $GLOBALS['gStudent']->is_graduate,
          'graduate_year' => $GLOBALS['gStudent']->graduate_year,
          'school_name' => $GLOBALS['gStudent']->school_name,
          'major' => $GLOBALS['gStudent']->major,
          'exam_province' => $GLOBALS['gStudent']->exam_province,
        ];

        $professorRates = $this->professorRateService->getRatesByStudentId($GLOBALS['gStudent']->student_id);
        $professorRatesInfo = [];
        foreach ($professorRates as $rate) {
            $professorRatesInfo["$rate->created_at"] = [
              'rate_type' => 'professor',
              'professor_rate_id' => $rate->school->professor_rate_id,
              'school_name' => $rate->school->school_name,
              'professor_name' => $rate->professor->professor_full_name,
              'course_code' => $rate->course_code,
              'course_name' => $rate->course_name,
              'course_category_name' => $rate->course_category_name,
              'is_attend' => $rate->is_attend,
              'difficult_level' => $rate->difficult_level,
              'homework_num' => $rate->homework_num,
              'written_homework_num' => $rate->written_homework_num,
              'quiz_num' => $rate->quiz_num,
              'course_related_quiz' => $rate->course_related_quiz,
              'spend_course_time_at_week' => $rate->spend_course_time_at_week,
              'grade' => $rate->grade,
              'comment' => $rate->comment,
              'create_student_id' => $rate->create_student_id,
              'tag' => $rate->tag,
              'effort' => round($rate->effort,1),
            ];
        }


        $schoolRates = $this->schoolRateService->getRatesByStudentId($GLOBALS['gStudent']->student_id);
        $schoolRatesInfo = [];
        foreach ($schoolRates as $rate) {
            $schoolRatesInfo["$rate->created_at"] = [
              'rate_type' => 'school',
              'school_rate_id' => $rate->schoolDistrict->school_rate_id,
              'school_district_name' => $rate->schoolDistrict->school_district_name,
              'school_name' => $rate->school->school_name,
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
              'student_name' => $rate->student->name,
              'create_student_id' => $rate->create_student_id,
              'score' => round($rate->score,1),
            ];
        }

        $ratesInfo = $schoolRatesInfo + $professorRatesInfo;
        krsort($ratesInfo);


        $data = [
          'success' => true,
          'data' => [
            'student' => $student,
            'ratesInfo' => $ratesInfo,
          ]
        ];

        return \Response::json($data);

    }


    public function getStudentById(Request $request){

        $studentId = $request->get('student_id');
        if (!$studentId) {
            throw new APIException("miss student id", APIException::MISS_PARAM);
        }

        $student = $this->studentService->getStudentById($studentId);

        $schoolStatus = "未知的学校";
        if($student->school_name){
            $school = $this->schoolService->getSchoolByName($student->school_name);
            if($school){
                if($school->country->country_name=='中国'){
                    $schoolStatus = "国内";
                }else{
                    $schoolStatus = "国外";
                }

            }
        }

        $studentInfo = [
          'name' => $student->name,
          'email' => $student->email,
          'is_email_edu' => $student->is_email_edu,
          'gender' => $student->gender,
          'mobile' => $student->mobile,
          'education_status' => $student->education_status,
          'is_graduate' => $student->is_graduate,
          'graduate_year' => $student->graduate_year,
          'school_name' => $student->school_name,
          'school_status' => $schoolStatus,
          'major' => $student->major,
          'exam_province' => $student->exam_province,
        ];

        $professorRates = $this->professorRateService->getRatesByStudentId($student->student_id);
        $professorRatesInfo = [];
        foreach ($professorRates as $rate) {
            $professorRatesInfo["$rate->created_at"] = [
              'rate_type' => 'professor',
              'professor_rate_id' => $rate->school->professor_rate_id,
              'school_name' => $rate->school->school_name,
              'professor_name' => $rate->professor->professor_full_name,
              'course_code' => $rate->course_code,
              'course_name' => $rate->course_name,
              'course_category_name' => $rate->course_category_name,
              'is_attend' => $rate->is_attend,
              'difficult_level' => $rate->difficult_level,
              'homework_num' => $rate->homework_num,
              'written_homework_num' => $rate->written_homework_num,
              'quiz_num' => $rate->quiz_num,
              'course_related_quiz' => $rate->course_related_quiz,
              'spend_course_time_at_week' => $rate->spend_course_time_at_week,
              'grade' => $rate->grade,
              'comment' => $rate->comment,
              'create_student_id' => $rate->create_student_id,
              'tag' => $rate->tag,
              'effort' => round($rate->effort,1),
            ];
        }


        $schoolRates = $this->schoolRateService->getRatesByStudentId($student->student_id);
        $schoolRatesInfo = [];
        foreach ($schoolRates as $rate) {
            $schoolRatesInfo["$rate->created_at"] = [
              'rate_type' => 'school',
              'school_rate_id' => $rate->schoolDistrict->school_rate_id,
              'school_district_name' => $rate->schoolDistrict->school_district_name,
              'school_name' => $rate->school->school_name,
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
              'student_name' => $rate->student->name,
              'create_student_id' => $rate->create_student_id,
              'score' => round($rate->score,1),
            ];
        }

        $ratesInfo = $schoolRatesInfo + $professorRatesInfo;
        krsort($ratesInfo);


        $data = [
          'success' => true,
          'data' => [
            'student' => $studentInfo,
            'ratesInfo' => $ratesInfo,
          ]
        ];

        return \Response::json($data);


    }


    public function getStudentMessage()
    {
        $student = $GLOBALS['gStudent'];
        $messages = $this->messageService->getMessagesByStudentId($student->student_id);
        $messageInfo = [];
        foreach ($messages as $message) {
            $tmp = json_decode($message->message_content,true);
            $tmp['created_at'] = $message->created_at->format('Y-m-d H:i:s');
            $messageInfo[] = $tmp;
        }

        $data = [
          'success' => true,
          'data' => [
            'messageInfo' => $messageInfo,
          ]
        ];

        return \Response::json($data);
    }


    public function setPoints()
    {
        $delta = $_GET['delta'];
        $data = $this->studentService->setPoints($delta, '11', $GLOBALS['gStudent']);
        var_dump($data);
    }

    public function getPoints()
    {
        $data = $this->studentService->getPoints($GLOBALS['gStudent']);
        var_dump($data);
    }

    public function getAllByName(Request $request)
    {

        $name = $request->get('name');
        if (!$name) {
            throw new APIException("miss param name", APIException::MISS_PARAM);
        }
        $page = $request->get('page', 1);
        $limit = $request->get('pageSize', 10);;


        $countSql = <<<sql
        select count(*) as total from 
        (
        (select professor_full_name as name  from `professor` where `professor_full_name` like '%$name%') union all
        (select school_name as name   from `school` where (`school_name` like '%$name%' or `school_nick_name` like '%$name%' or `school_nick_name_two` like '%$name%'))
        ) as a
sql;
        $res = DB::select($countSql);
        $total = $res[0]->total;

        if ($total == 0) {

            $data = [
              'success' => true,
              'data' => [
                'total' => 0,
                'pageNum' => 0,
                'perPageNum' => $limit,
                'currentPage' => $page,
                'prevPage' => null,
                'nextPage' => null,
                'res' => null,
              ]
            ];

            return \Response::json($data);


        }


        $offset = ($page - 1) * $limit;
        $pageNum = ceil($total / $limit);
        if ($page == 1) {
            $prevPage = null;
        } else {
            $prevPage = $page - 1;
        }
        if ($page == $pageNum) {
            $nextPage = null;
        } else {
            $nextPage = $page + 1;
        }


        $resSql = <<<sql
        select * from 
        (
        (select professor_id as id,professor_full_name as name,school_id as col1,college_id as col2,professor_web_site as col3,'professor' as type  from `professor` 
        where `professor_full_name` like '%$name%') 
        union all
        (select school_id as id ,school_name as name ,country_id as col1,province_id as col2,city_id as col3,'school' as type   from `school` 
        where (`school_name` like '%$name%' or `school_nick_name` like '%$name%' or `school_nick_name_two` like '%$name%'))
        ) as a limit $limit offset $offset
sql;


        $res = DB::select($resSql);
        $tmp = [];


        foreach ($res as $item) {
            if ($item->type == "professor") {
                $school = $this->schoolService->getSchoolById($item->col1);
                $college = $this->collegeService->getCollegeById($item->col2);
                $tmp[] = [
                  'professor_id' => $item->id,
                  'professor_full_name' => $item->name,
                  'school_name' => !empty($school) ? $school->school_name : "",
                  'college_name' => !empty($college) ? $college->college_name : "",
                  'professor_web_site' => $item->col3,
                  'type' => 'professor',
                ];
            }
            if ($item->type == "school") {
                $country = $this->countryService->getCountryById($item->col1);
                $province = $this->provinceService->getProvinceById($item->col2);
                $city = $this->cityService->getCityById($item->col3);
                $tmp[] = [
                  'school_id' => $item->id,
                  'school_name' => $item->name,
                  'country_name' => !empty($country) ? $country->country_name : "",
                  'province_name' => !empty($province) ? $province->province_name : "",
                  'city_name' => !empty($city) ? $city->city_name : "",
                  'type' => 'school',
                ];
            }
        }
        $res = $tmp;

        $data = [
          'success' => true,
          'data' => [
            'total' => $total,
            'pageNum' => $pageNum,
            'perPageNum' => $limit,
            'currentPage' => $page,
            'prevPage' => $prevPage,
            'nextPage' => $nextPage,
            'res' => $res,
          ]
        ];

        return \Response::json($data);

    }

}
