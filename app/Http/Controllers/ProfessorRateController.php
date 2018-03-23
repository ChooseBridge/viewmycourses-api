<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\Service\Abstracts\ProfessorCourseServiceAbstract;
use App\Service\Abstracts\ProfessorRateServiceAbstract;
use App\Service\Abstracts\ProfessorServiceAbstract;
use App\Service\Abstracts\SchoolCourseCategoryServiceAbstract;
use Illuminate\Http\Request;

class ProfessorRateController extends Controller
{

    protected $professorRateService;
    protected $professorService;
    protected $professorCourseService;
    protected $schoolCourseCategoryService;

    public function __construct(
      ProfessorRateServiceAbstract $professorRateService,
      ProfessorServiceAbstract $professorService,
      ProfessorCourseServiceAbstract $professorCourseService,
      SchoolCourseCategoryServiceAbstract $schoolCourseCategoryService
    ) {
        $this->professorRateService = $professorRateService;
        $this->professorService = $professorService;
        $this->professorCourseService = $professorCourseService;
        $this->schoolCourseCategoryService = $schoolCourseCategoryService;
    }

//backend

    public function index()
    {
        $rates = $this->professorRateService->getRatesForPage();
        return view('professor_rate.index', [
          'rates' => $rates
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

//api

    public function createRate(Request $request)
    {

        //待处理用户权限处理

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
        if ($res){
            $data = [
              'success' => true,
              'data' => 'thumbs up success'
            ];
        }else{
            $data = [
              'success' => false,
              'data' => 'thumbs up false'
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
        if ($res){
            $data = [
              'success' => true,
              'data' => 'thumbs down success'
            ];
        }else{
            $data = [
              'success' => false,
              'data' => 'thumbs dowm false'
            ];
        }
        return \Response::json($data);
    }

}
