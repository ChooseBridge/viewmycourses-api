<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\Service\Abstracts\ProfessorCourseServiceAbstract;
use App\Service\Abstracts\ProfessorRateServiceAbstract;
use App\Service\Abstracts\ProfessorServiceAbstract;
use App\Service\Abstracts\SchoolCourseCategoryServiceAbstract;
use App\Service\Abstracts\StudentServiceAbstract;
use Illuminate\Http\Request;

class ProfessorRateController extends Controller
{

    protected $professorRateService;
    protected $professorService;
    protected $professorCourseService;
    protected $schoolCourseCategoryService;
    protected $studentService;

    public function __construct(
      ProfessorRateServiceAbstract $professorRateService,
      ProfessorServiceAbstract $professorService,
      ProfessorCourseServiceAbstract $professorCourseService,
      SchoolCourseCategoryServiceAbstract $schoolCourseCategoryService,
      StudentServiceAbstract $studentService
    ) {
        $this->professorRateService = $professorRateService;
        $this->professorService = $professorService;
        $this->professorCourseService = $professorCourseService;
        $this->schoolCourseCategoryService = $schoolCourseCategoryService;
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
            $rates = $this->professorRateService->getRatesForPage(10,$callback);
        }else{
            $rates = $this->professorRateService->getRatesForPage();
        }


        return view('professor_rate.index', [
          'rates' => $rates,
          'check_status' => $check_status,
        ]);
    }

    public function detail(Request $request)
    {
        $professorRateId = $request->get('professor_rate_id');
        $rate = $this->professorRateService->getRateById($professorRateId);
        return view('professor_rate.detail', [
          'rate' => $rate
        ]);
    }


    public function approve(Request $request)
    {
        $professorRateId = $request->get('professor_rate_id');
        $this->professorRateService->approveRateById($professorRateId);
        return redirect(route('backend.professor-rate.index'));
    }

    public function reject(Request $request)
    {
        $professorRateId = $request->get('professor_rate_id');
        $this->professorRateService->rejectRateById($professorRateId);
        return redirect(route('backend.professor-rate.index'));
    }

    public function delete(Request $request){

        $professorRateId = $request->get('professor_rate_id');
        $rate = $this->professorRateService->getRateById($professorRateId);
        if($rate){
            $rate->delete();
        }
        return redirect(route('backend.professor-rate.index'));
    }

//api

    public function createRate(Request $request)
    {

        if(!$this->studentService->currentStudentIsVip()){
            throw new APIException("此操作需要会员权限",APIException::IS_NOT_VIP);
        }

        $data = $request->all();


        if (!isset($data['professor_id'])) {
            throw new APIException("professor_id 参数缺失", APIException::MISS_PARAM);
        }

        $professor = $this->professorService->getProfessorById($data['professor_id']);
        if (!$professor) {
            throw new APIException("未知的教授", APIException::DATA_EXCEPTION);
        }
        $data['school_id'] = $professor->school_id;
        $data['college_id'] = $professor->college_id;

        if (isset($data['course_id'])) {

            $course = $this->professorCourseService->getCourseById($data['course_id']);
            if (!$course) {
                throw new APIException("未知course code", APIException::DATA_EXCEPTION);
            }
            $data['course_code'] = $course->course_code;

        }

        if (isset($data['course_category_id'])) {
            $courseCategory = $this->schoolCourseCategoryService->getCourseCategoryById($data['course_category_id']);
            if (!$courseCategory) {
                throw new APIException("未知course category name", APIException::DATA_EXCEPTION);
            }
            $data['course_category_name'] = $courseCategory->course_category_name;
        }


        $data['create_student_id'] = $GLOBALS['gStudent']->student_id;

        $validator = $this->professorRateService->validatorForCreate($data);
        if ($validator !== true) {
            $message = $validator->errors()->first();
            throw new APIException($message, APIException::ERROR_PARAM);
        }

        $rate = $this->professorRateService->createRate($data);

        if (!$rate) {
            throw new APIException('操作异常', APIException::OPERATION_EXCEPTION);
        }

        $data = [
          'success' => true,
          'data' => '创建成功'
        ];

        return \Response::json($data);

    }

    public function thumbsUpRate(Request $request)
    {

        $student = $GLOBALS['gStudent'];
        $professorRateId = $request->get('professor_rate_id');
        if (!$professorRateId) {
            throw new APIException("miss params professor rate id");
        }
        $res = $this->professorRateService->thumbsUpRateById($professorRateId,$student);
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


    public function thumbsDownRate(Request $request)
    {
        $student = $GLOBALS['gStudent'];
        $professorRateId = $request->get('professor_rate_id');
        if (!$professorRateId) {
            throw new APIException("miss params professor rate id");
        }
        $res = $this->professorRateService->thumbsDownRateById($professorRateId,$student);
        if ($res['res']) {
            $data = [
              'success' => true,
              'data' => [
                'msg' => 'thumbs down success',
                'num' => $res['num'],
              ]
            ];
        } else {
            $data = [
              'success' => false,
              'data' => [
                'msg' => 'thumbs down false',
              ]
            ];
        }
        return \Response::json($data);
    }

}
