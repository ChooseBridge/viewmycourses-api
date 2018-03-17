<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\Service\Abstracts\SchoolDistrictServiceAbstract;
use App\Service\Abstracts\SchoolRateServiceAbstract;
use Illuminate\Http\Request;

class SchoolRateController extends Controller
{
    //

    protected $schoolRateService;
    protected $schoolDistrictService;

    public function __construct(
      SchoolRateServiceAbstract $schoolRateService,
      SchoolDistrictServiceAbstract $schoolDistrictService
    ) {
        $this->schoolRateService = $schoolRateService;
        $this->schoolDistrictService = $schoolDistrictService;
    }

    public function createRate(Request $request)
    {
        //待处理用户权限处理

        $data = $request->all();

        if (!isset($data['school_district_id'])) {
            throw new APIException("school district id 参数缺失", APIException::MISS_PARAM);
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
}
