<?php

namespace App\Http\Controllers;

use App\College;
use App\Exceptions\APIException;
use App\Professor;
use App\Service\Abstracts\CollegeServiceAbstract;
use App\Service\Abstracts\ProfessorCourseServiceAbstract;
use App\Service\Abstracts\ProfessorRateServiceAbstract;
use App\Service\Abstracts\ProfessorServiceAbstract;
use App\Service\Abstracts\SchoolServiceAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfessorController extends Controller
{
    //

    protected $professorService;
    protected $schoolService;
    protected $collegeService;
    protected $professorRateService;
    protected $professorCourseService;

    public function __construct(
      ProfessorServiceAbstract $professorService,
      SchoolServiceAbstract $schoolService,
      CollegeServiceAbstract $collegeService,
      ProfessorRateServiceAbstract $professorRateService,
      ProfessorCourseServiceAbstract $professorCourseService
    ) {
        $this->professorService = $professorService;
        $this->schoolService = $schoolService;
        $this->collegeService = $collegeService;
        $this->professorRateService = $professorRateService;
        $this->professorCourseService = $professorCourseService;
    }


//backend

    public function index()
    {

        $professors = $this->professorService->getProfessorsForPage();
        return view('professor.index', [
          'professors' => $professors
        ]);
    }

    public function addProfessor(Request $request)
    {
        if ($request->isMethod('POST')) {

            $data = $request->all();
            $validator = $this->professorService->validatorForCreate($data);
            if ($validator !== true) {
                return redirect(route('backend.professor.add.get'))
                  ->withErrors($validator);
            }
            $data['professor_full_name'] = $data['professor_fisrt_name'] . $data['professor_second_name'];
            $data['create_user_id'] = Auth::user()->id;
            $data['check_status'] = Professor::APPROVE_CHECK;
            $this->professorService->createProfessor($data);
            return redirect(route("backend.professor.index"));

        }

        $schools = $this->schoolService->getAllCheckedSchools();
        return view('professor.add', [
          'schools' => $schools
        ]);
    }

    public function approve(Request $request)
    {

        $professor_id = $request->get('professor_id');
        $this->professorService->approveProfessorById($professor_id);
        return redirect(route('backend.professor.index'));

    }

    public function reject(Request $request)
    {

        $professor_id = $request->get('professor_id');
        $this->professorService->rejectProfessorById($professor_id);
        return redirect(route('backend.professor.index'));

    }

//api

    public function createProfessor(Request $request)
    {
        //待处理创建权限

        $data = $request->all();
        $validator = $this->professorService->validatorForCreate($data);
        if ($validator !== true) {
            $message = $validator->errors()->first();
            throw new APIException($message, APIException::ERROR_PARAM);
        }

        $college = $this->collegeService->getCollegeById($data['college_id']);
        if (!$college || $college->school->school_id != $data['school_id']) {
            throw new APIException('非法操作', APIException::ILLGAL_OPERATION);
        }

        $data['professor_full_name'] = $data['professor_fisrt_name'] . $data['professor_second_name'];
        $data['create_student_id'] = $GLOBALS['gStudent']->student_id;
        $data['check_status'] = Professor::PENDING_CHECK;
        $professor = $this->professorService->createProfessor($data);
        if (!$professor) {
            throw new APIException('操作异常', APIException::OPERATION_EXCEPTION);
        }

        $data = [
          'success' => true,
          'data' => '创建成功'
        ];

        return \Response::json($data);
    }

    /*
     * 根据查询条件获取教授分页信息
     */
    public function getProfessorByCondition(Request $request)
    {

        //待处理搜索权限

        $professors = [];
        $schoolId = $request->get('school_id');
        $professorName = $request->get('professor_name');
        $collegeId = $request->get('college_id');

        $queryCallBack = function ($query) use ($schoolId, $professorName, $collegeId) {

            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }
            if ($professorName) {
                $query->where('professor_full_name', 'like', "%$professorName%");
            }
            if ($collegeId) {
                $query->where('college_id', $collegeId);
            }

        };
        $result = $this->professorService->getProfessorsForPage(1, $queryCallBack);
        foreach ($result as $professor) {
            $professors[] = [
              'professor_id' => $professor->professor_id,
              'professor_full_name' => $professor->professor_full_name,
              'professor_web_site' => $professor->professor_web_site,
            ];
        }

        $pageInfo = $result->appends([
          'school_id' => $schoolId,
          'professor_name' => $professorName,
          'college_id' => $collegeId,
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
            'professors' => $professors,
            'pageInfo' => $pageInfo,
          ]
        ];
        return \Response::json($data);
    }

    public function getProfessorDetail(Request $request)
    {

        $professorId = $request->get('professor_id');
        if (!$professorId) {
            throw new APIException('参数 professor id 缺失', APIException::MISS_PARAM);
        }
        $professor = $this->professorService->getProfessorById($professorId);
        if (!$professor) {
            throw new APIException('未知的教授', APIException::DATA_EXCEPTION);
        }


        $rateInfo = [];
        $rates = $this->professorRateService->getRatesByProfessorId($professorId);

        $tagsStr = "";
        $calculateAllEffort = [];
        $calculateCourseEffort = [];

        foreach ($rates as $rate) {

            if (!isset($calculateAllEffort['effort'])) {
                $calculateAllEffort['effort'] = $rate->effort;
                $calculateAllEffort['num'] = 1;
            } else {
                $calculateAllEffort['effort'] += $rate->effort;
                $calculateAllEffort['num'] += 1;
            }

            if (!isset($calculateCourseEffort[$rate->course_code]['effort'])) {
                $calculateCourseEffort[$rate->course_code]['effort'] = $rate->effort;
                $calculateCourseEffort[$rate->course_code]['num'] = 1;
            } else {
                $calculateCourseEffort[$rate->course_code]['effort'] += $rate->effort;
                $calculateCourseEffort[$rate->course_code]['num'] += 1;
            }

            $professorEffort = 0;
            if(isset($calculateAllEffort['effort'])){
                $professorEffort = $calculateAllEffort['effort']/$calculateAllEffort['num'];
            }

            $professorInfo = [
              'professor_full_name' => $professor->professor_full_name,
              'school' => $professor->school->school_name,
              'country' => $professor->school->country->country_name,
              'province' => $professor->school->province->province_name,
              'city' => $professor->school->city->city_name,
              'effort' => $professorEffort,
            ];


            $rateInfo[] = [
              'course_code' => $rate->course_code,
              'course_name' => $rate->course_name,
              'course_category_name' => $rate->course_category_name,
              'is_attend' => $rate->is_attend,
              'difficult_level' => $rate->difficult_level,
              'homework_num' => $rate->homework_num,
              'quiz_num' => $rate->quiz_num,
              'course_related_quiz' => $rate->course_related_quiz,
              'spend_course_time_at_week' => $rate->spend_course_time_at_week,
              'grade' => $rate->grade,
              'comment' => $rate->comment,
              'tag' => $rate->tag,
              'effort' => $rate->effort,
            ];
            $tagsStr .= $rate->tag . ",";
        }


        $tags = explode(',', rtrim($tagsStr, ','));
        $tagsInfo = [];
        foreach ($tags as $tag) {
            if (!isset($tagsInfo[$tag])) {
                $tagsInfo[$tag] = 1;
            } else {
                $tagsInfo[$tag] += 1;
            }
        }


        $coursesInfo = [];
        $courses = $this->professorCourseService->getCoursesByProfessorId($professorId);
        foreach ($courses as $course) {
            $effort = 0;
            if(isset($calculateCourseEffort[$course->course_code])){
                $effort = $calculateCourseEffort[$course->course_code]['effort']/$calculateCourseEffort[$course->course_code]['num'];
            }
            $coursesInfo = [
              'course_id' => $course->course_id,
              'course_code' => $course->course_code,
              'effort' => $effort,
            ];
        }

        $data = [
          'success' => true,
          'data' => [
            'professorInfo' => $professorInfo,
            'coursesInfo' => $coursesInfo,
            'rateInfo' => $rateInfo,
            'tagsInfo' => $tagsInfo,
          ]
        ];
        return \Response::json($data);


    }
}
