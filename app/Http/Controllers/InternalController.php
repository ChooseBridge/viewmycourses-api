<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\Service\Abstracts\SchoolServiceAbstract;
use Illuminate\Http\Request;

class InternalController extends Controller
{
    //
    protected $schoolService;

    public function __construct(SchoolServiceAbstract $schoolService)
    {
        $this->schoolService = $schoolService;
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
}
