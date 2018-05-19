<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\Service\Abstracts\SchoolServiceAbstract;
use App\Service\Abstracts\StudentServiceAbstract;
use Illuminate\Http\Request;

class InternalController extends Controller
{
    //
    protected $schoolService;
    protected $studentService;

    public function __construct(
      SchoolServiceAbstract $schoolService,
      StudentServiceAbstract $studentService
    ) {
        $this->schoolService = $schoolService;
        $this->studentService = $studentService;
    }

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

    public function setVipTime(Request $request){

        $uid= $request->get('uid', null);
        $score= $request->get('score', null);

        if (!$uid) {
            throw  new APIException("缺失参数uid", APIException::MISS_PARAM);
        }

        $student = $this->studentService->getStudentByUCenterUId($uid);

        if(!$student){
            throw  new APIException("未知的用户信息", APIException::DATA_EXCEPTION);
        }

        if (!$score) {
            throw  new APIException("缺失参数score", APIException::MISS_PARAM);
        }

        if($score % 300 != 0){
            throw  new APIException("参数score格式错误", APIException::ERROR_PARAM);
        }

        $num = $score/300;
        if($student->is_vip == 1){

            $time = strtotime($student->vip_expire_time)+3600*24*180*$num;
            $data = [
              'vip_expire_time'=>date("Y-m-d H:i:s", $time),
            ];
        }else{
            $time = time()+3600*24*180*$num;
            $data = [
              'vip_expire_time'=>date("Y-m-d H:i:s", $time),
              'is_vip'=>1,
            ];
        }

        $res = $this->studentService->updateStudent($student,$data);

        if($res){
            return \Response::json([
              'success' => true,
              'data' => [
                'msg' => 'set success',
              ]
            ]);
        }else{
            return \Response::json([
              'success' => false,
              'data' => [
                'msg' => 'set fail',
              ]
            ]);
        }

    }
}
