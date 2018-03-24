<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\Service\Abstracts\SchoolDistrictServiceAbstract;
use App\Service\Abstracts\SchoolRateServiceAbstract;
use App\Service\Abstracts\SchoolServiceAbstract;
use Illuminate\Http\Request;

class SchoolRateController extends Controller
{
    //

    protected $schoolRateService;
    protected $schoolDistrictService;
    protected $schoolService;

    public function __construct(
      SchoolRateServiceAbstract $schoolRateService,
      SchoolDistrictServiceAbstract $schoolDistrictService,
      SchoolServiceAbstract $schoolService
    ) {
        $this->schoolRateService = $schoolRateService;
        $this->schoolDistrictService = $schoolDistrictService;
        $this->schoolService = $schoolService;
    }


//backend

    public function index()
    {
        $rates = $this->schoolRateService->getRatesForPage();
        return view('school_rate.index', [
          'rates' => $rates
        ]);
    }

    public function detail(Request $request)
    {
        $schoolRateId = $request->get('school_rate_id');
        $rate = $this->schoolRateService->getRateById($schoolRateId);
        return view('school_rate.detail', [
          'rate' => $rate
        ]);
    }

    public function approve(Request $request)
    {
        $schoolRateId = $request->get('school_rate_id');
        $this->schoolRateService->approveRateById($schoolRateId);
        return redirect(route('backend.school-rate.index'));
    }

    public function reject(Request $request)
    {
        $schoolRateId = $request->get('school_rate_id');
        $this->schoolRateService->rejectRateById($schoolRateId);
        return redirect(route('backend.school-rate.index'));
    }

//api

    public function createRate(Request $request)
    {
        //待处理用户权限处理

        $data = $request->all();

        if (!isset($data['school_district_id'])) {
            throw new APIException("school district id 参数缺失", APIException::MISS_PARAM);
        }
        if (!isset($data['school_id'])) {
            throw new APIException("school id 参数缺失", APIException::MISS_PARAM);
        }

        $isChecked = $this->schoolService->isCheckedById($data['school_id']);
        if (!$isChecked) {
            throw new APIException("未被审核的学校不能点评", APIException::DATA_EXCEPTION);
        }


        $district = $this->schoolDistrictService->getDistrictById($data['school_district_id']);
        if (!$district) {
            throw new APIException("未知的校区", APIException::DATA_EXCEPTION);
        }


        $data['create_student_id'] = $GLOBALS['gStudent']->student_id;


        $validator = $this->schoolRateService->validatorForCreate($data);
        if ($validator !== true) {
            $message = $validator->errors()->first();
            throw new APIException($message, APIException::ERROR_PARAM);
        }

        $rate = $this->schoolRateService->createRate($data);

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
        $schoolRateId = $request->get('school_rate_id');
        if (!$schoolRateId) {
            throw new APIException("miss params school rate id");
        }
        $res = $this->schoolRateService->thumbsUpRateById($schoolRateId, $student);
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
        $schoolRateId = $request->get('school_rate_id');
        if (!$schoolRateId) {
            throw new APIException("miss params school rate id");
        }
        $res = $this->schoolRateService->thumbsDownRateById($schoolRateId, $student);
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
