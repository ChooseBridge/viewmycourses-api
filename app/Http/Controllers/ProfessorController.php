<?php

namespace App\Http\Controllers;

use App\College;
use App\Exceptions\APIException;
use App\Professor;
use App\ProfessorComment;
use App\SchoolCourseCategory;
use App\Service\Abstracts\CollegeServiceAbstract;
use App\Service\Abstracts\ProfessorCourseServiceAbstract;
use App\Service\Abstracts\ProfessorRateServiceAbstract;
use App\Service\Abstracts\ProfessorServiceAbstract;
use App\Service\Abstracts\SchoolCourseCategoryServiceAbstract;
use App\Service\Abstracts\SchoolServiceAbstract;
use App\Service\Abstracts\StudentServiceAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Overtrue\Pinyin\Pinyin;

class ProfessorController extends Controller
{
    //

    protected $professorService;
    protected $schoolService;
    protected $collegeService;
    protected $professorRateService;
    protected $professorCourseService;
    protected $schoolCourseCategory;
    protected $studentService;

    public function __construct(
      ProfessorServiceAbstract $professorService,
      SchoolServiceAbstract $schoolService,
      CollegeServiceAbstract $collegeService,
      ProfessorRateServiceAbstract $professorRateService,
      ProfessorCourseServiceAbstract $professorCourseService,
      SchoolCourseCategoryServiceAbstract $schoolCourseCategory,
      StudentServiceAbstract $studentService
    ) {
        $this->professorService = $professorService;
        $this->schoolService = $schoolService;
        $this->collegeService = $collegeService;
        $this->professorRateService = $professorRateService;
        $this->professorCourseService = $professorCourseService;
        $this->schoolCourseCategory = $schoolCourseCategory;
        $this->studentService = $studentService;
    }


//backend

    public function index(Request $request)
    {
        $check_status = $request->get('check_status');
        if($check_status != null){

            $callback = function ($query) use ($check_status){
                $query->where('check_status',$check_status);
            };
        }

        if(isset($callback)){
            $professors = $this->professorService->getProfessorsForPage(10,$callback);
        }else{
            $professors = $this->professorService->getProfessorsForPage();
        }


        return view('professor.index', [
          'professors' => $professors,
          'check_status' => $check_status
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

            if(preg_match("/^[a-zA-Z\s]+$/",$data['professor_fisrt_name'] . $data['professor_second_name'])){
                $data['professor_full_name'] =   $data['professor_second_name']." ".$data['professor_fisrt_name'];
            }else{
                $data['professor_full_name'] = $data['professor_fisrt_name'] . $data['professor_second_name'];
            }

            $pinyin = new Pinyin();
            $data['p_sort'] = substr($pinyin->abbr($data['professor_full_name']),0,1);

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

    public function updateProfessor(Request $request){

        $professorId = $request->get('professor_id');
        $professor = $this->professorService->getProfessorById($professorId);
        if(!$professorId || !$professor){
            return redirect(route("backend.professor.index"));
        }
        if ($request->isMethod('POST')) {

            $data = $request->all();
            if(!isset($data['professor_web_site'])){
                $data['professor_web_site'] = "";
            }
            if(preg_match("/^[a-zA-Z\s]+$/",$data['professor_fisrt_name'] . $data['professor_second_name'])){
                $data['professor_full_name'] =   $data['professor_second_name']." ".$data['professor_fisrt_name'];
            }else{
                $data['professor_full_name'] = $data['professor_fisrt_name'] . $data['professor_second_name'];
            }

            $pinyin = new Pinyin();
            $data['p_sort'] = substr($pinyin->abbr($data['professor_full_name']),0,1);

            $professor->update($data);
            return redirect(route("backend.professor.index"));
        }

        $schools = $this->schoolService->getAllCheckedSchools();
        $colleges = $this->collegeService->getCollegesBySchoolId($professor->school_id);
        return view('professor.update', [
          'schools' => $schools,
          'professor' => $professor,
          'colleges' => $colleges,
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

    public function showComment(Request $request){
        $professor_id = $request->get('professor_id');
        $professor = $this->professorService->getProfessorById($professor_id);
        return view('professor.comment', [
          'professor' => $professor,
        ]);
    }


    public function commentIndex(){

        $comments  = ProfessorComment::paginate(10);
        return view('professor.comment_index', [
          'comments' => $comments,
        ]);
    }

//api

    public function createProfessor(Request $request)
    {
        if (!$this->studentService->currentStudentIsVip()) {
            throw new APIException("此操作需要会员权限", APIException::IS_NOT_VIP);
        }

        $data = $request->all();
        $validator = $this->professorService->validatorForCreate($data);
        if ($validator !== true) {
            $message = $validator->errors()->first();
            throw new APIException($message, APIException::ERROR_PARAM);
        }

//        $college = $this->collegeService->getCollegeById($data['college_id']);
//        if (!$college || $college->school->school_id != $data['school_id']) {
//            throw new APIException('非法操作', APIException::ILLGAL_OPERATION);
//        }

        if(preg_match("/^[a-zA-Z\s]+$/",$data['professor_fisrt_name'] . $data['professor_second_name'])){
            $data['professor_full_name'] =   $data['professor_second_name']." ".$data['professor_fisrt_name'];
        }else{
            $data['professor_full_name'] = $data['professor_fisrt_name'] . $data['professor_second_name'];
        }

        $pinyin = new Pinyin();
        $data['p_sort'] = substr($pinyin->abbr($data['professor_full_name']),0,1);


        $data['create_student_id'] = $GLOBALS['gStudent']->student_id;
        $data['check_status'] = Professor::PENDING_CHECK;

        //代表上传的是文字
        if(intval($data['college_id']) == 0){
            $data['college_name'] = $data['college_id'];
            $data['college_id'] = 0;
        }
        $professor = $this->professorService->createProfessor($data);
        if (!$professor) {
            throw new APIException('操作异常', APIException::OPERATION_EXCEPTION);
        }

        $data = [
          'success' => true,
          'data' => [
            'id'=>$professor->professor_id
          ]
        ];

        return \Response::json($data);
    }

    /*
     * 根据查询条件获取教授分页信息
     */
    public function getProfessorByCondition(Request $request)
    {

        if (!$this->studentService->currentStudentIsVip()) {
            throw new APIException("此操作需要会员权限", APIException::IS_NOT_VIP);
        }

        $professors = [];
        $schoolName = $request->get('school_name');
        $professorName = $request->get('professor_name');
        $schoolId = $request->get('school_id');
        $collegeId = $request->get('college_id');
        $collegeName = $request->get('college_name');
        $limit = $request->get('pageSize', 10);;

        $queryCallBack = function ($query) use ($professorName, $schoolId, $collegeId) {
            if ($professorName) {
                $query->where('professor_full_name', 'like', "%$professorName%");
            }
            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }
            if ($collegeId) {
                $query->where('college_id', $collegeId);
            }

        };

        $join = [];
        if($schoolName){
            $join['school'] =  function ($query) use ($schoolName) {
                $query->where('school_name', 'like', "%" . $schoolName . "%");
                $query->orWhere('school_nick_name', 'like', "%" . $schoolName . "%");
                $query->orWhere('school_nick_name_two', 'like', "%" . $schoolName . "%");
            };
        }
        if($collegeName){
            $join['college'] = function ($query) use ($collegeName) {
                $query->where('college_name', 'like', "%" . $collegeName . "%");
            };
        }
        if(empty($join)){
            $join = null;
        }

        $result = $this->professorService->getProfessorsForPage($limit, $queryCallBack, $join);
        foreach ($result as $professor) {
            $professors[] = [
              'professor_id' => $professor->professor_id,
              'professor_full_name' => $professor->professor_full_name,
              'professor_web_site' => $professor->professor_web_site,
              'college' => $professor->college_id == 0 ?$professor->college_id:$professor->college->college_name,
              'school_name' => $professor->school->school_name,
            ];
        }

        $pageInfo = $result->appends([
          'school_name' => $schoolName,
          'professor_name' => $professorName,
          'college_name' => $collegeName,
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
        $rates = $this->professorRateService->getCheckedRatesByProfessorId($professorId);

        $tagsStr = "";
        $calculateAllEffort = [];
        $calculateCourseEffort = [];

        $max_thumbs_up_rate_id = 0;
        $max_thumbs_up = 0;
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


            $tmp = [
              'professor_rate_id' => $rate->professor_rate_id,
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
              'effort' => round($rate->effort, 1),
              'created_at' => $rate->created_at,
              'create_student_id' => $rate->create_student_id,
            ];

            //将点赞数最多的一条点评置顶，若点赞数相同，则置顶最新的点评
            if ($rate->thumbs_up != "") {
                $thumbsUpNum = count(explode(',', trim($rate->thumbs_up, ',')));
                if ($thumbsUpNum > $max_thumbs_up) {
                    $max_thumbs_up_rate_id = $rate->professor_rate_id;
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

            $rateInfo[$rate->professor_rate_id] = $tmp;
            $tagsStr .= $rate->tag . ",";
        }

        if($max_thumbs_up_rate_id != 0){
            $max_rate = $rateInfo[$max_thumbs_up_rate_id];
            unset($rateInfo[$max_thumbs_up_rate_id]);
            array_unshift($rateInfo,$max_rate);
        }
        $rateInfo = array_values($rateInfo);

        $professorEffort = 0;
        if (isset($calculateAllEffort['effort'])) {
            $professorEffort = $calculateAllEffort['effort'] / $calculateAllEffort['num'];
        }

        $professorInfo = [
          'professor_full_name' => $professor->professor_full_name,
          'professor_web_site' => $professor->professor_web_site,
          'school' => $professor->school->school_name,
          'college' => $professor->college->college_name,
          'country' => $professor->school->country->country_name,
          'province' => $professor->school->province->province_name,
          'city' => $professor->school->city->city_name,
          'effort' => round($professorEffort, 1),
        ];

        if ($professor->thumbs_up == "") {
            $professorInfo['thumbs_up_num'] = 0;
        } else {
            $professorInfo['thumbs_up_num'] = count(explode(',', trim($professor->thumbs_up, ',')));
        }

        if ($this->studentService->getCurrentStudent()) {
            if (strpos($professor->thumbs_up, ",{$GLOBALS['gStudent']->student_id},") === false) {
                $professorInfo["is_thumbs_up"] = false;
            } else {
                $professorInfo["is_thumbs_up"] = true;
            }
        }


        $tagsInfo = [];
        if($tagsStr != ""){
            $tags = explode(',', rtrim($tagsStr, ','));
            foreach ($tags as $tag) {
                if (!isset($tagsInfo[$tag])) {
                    $tagsInfo[$tag] = 1;
                } else {
                    $tagsInfo[$tag] += 1;
                }
            }
        }



        $coursesInfo = [];
        $courses = $this->professorCourseService->getCoursesByProfessorId($professorId);
        foreach ($courses as $course) {
            $effort = 0;
            if (isset($calculateCourseEffort[$course->course_code])) {
                $effort = $calculateCourseEffort[$course->course_code]['effort'] / $calculateCourseEffort[$course->course_code]['num'];
            }
            $coursesInfo[] = [
              'course_id' => $course->course_id,
              'course_code' => $course->course_code,
              'effort' => round($effort, 1),
            ];
        }

        $schoolCategorys = $this->schoolCourseCategory->getCourseCategorysBySchoolId($professor->school_id);
        $schoolCategoryInfo = [];

        foreach ($schoolCategorys as $schoolCategory) {
            $schoolCategoryInfo[] = [
              'course_category_id' => $schoolCategory->course_category_id,
              'course_category_name' => $schoolCategory->course_category_name,
            ];
        }

        $data = [
          'success' => true,
          'data' => [
            'professorInfo' => $professorInfo,
            'coursesInfo' => $coursesInfo,
            'schoolCategoryInfo' => $schoolCategoryInfo,
            'rateInfo' => $rateInfo,
            'tagsInfo' => $tagsInfo,
          ]
        ];
        return \Response::json($data);


    }

    public function thumbsUpProfessor(Request $request)
    {

        if (!$this->studentService->currentStudentIsVip()) {
            throw new APIException("此操作需要会员权限", APIException::IS_NOT_VIP);
        }

        $professorId = $request->get('professor_id');
        if (!$professorId) {
            throw new APIException("miss param professor id ", APIException::MISS_PARAM);
        }
        $student = $GLOBALS['gStudent'];
        $res = $this->professorService->thumbsUpProfessorById($professorId, $student);
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


    public function createComment(Request $request){

        $student = $this->studentService->getCurrentStudent();
        $professorId = $request->get('professor_id');
        $comment = $request->get('comment');
        if (!$professorId || !$comment) {
            throw new APIException("miss param professor_id or comment", APIException::MISS_PARAM);
        }
        ProfessorComment::create([
          'professor_id' => $professorId,
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
