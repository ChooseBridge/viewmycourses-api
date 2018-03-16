<?php

namespace App\Http\Controllers;

use App\Service\Abstracts\CollegeServiceAbstract;
use App\Service\Abstracts\SchoolServiceAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollegeController extends Controller
{
    protected $collegeService;
    protected $schoolService;

    public function __construct(
      CollegeServiceAbstract $collegeService,
      SchoolServiceAbstract $schoolService
    ) {
        $this->collegeService = $collegeService;
        $this->schoolService = $schoolService;
    }


// backend
    public function index()
    {

        $colleges = $this->collegeService->getCollegesForPage();
        return view('college.index', [
          'colleges' => $colleges
        ]);
    }

    public function addCollege(Request $request)
    {
        if($request->isMethod('POST')){

            $data = $request->all();
            $validator = $this->collegeService->validatorForCreate($data);
            if($validator !== true){
                return redirect(route('backend.college.add.get'))
                  ->withErrors($validator);
            }
            $data['create_user_id'] = Auth::user()->id;
            $this->collegeService->createCollege($data);
            return redirect(route("backend.college.index"));

        }

        $schools = $this->schoolService->getSchoolsGroupCountry();
        return view('college.add', [
          'schools' => $schools
        ]);
    }
}
