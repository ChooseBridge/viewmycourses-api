<?php

namespace App\Http\Controllers;

use App\College;
use App\Exceptions\APIException;
use App\Professor;
use App\Service\Abstracts\CollegeServiceAbstract;
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

    public function __construct(
      ProfessorServiceAbstract $professorService,
      SchoolServiceAbstract $schoolService,
      CollegeServiceAbstract $collegeService
    ) {
        $this->professorService = $professorService;
        $this->schoolService = $schoolService;
        $this->collegeService = $collegeService;
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
        $data = $request->all();
        $validator = $this->professorService->validatorForCreate($data);
        if ($validator !== true) {
            $message = $validator->errors()->first();
            throw new APIException($message, APIException::ERROR_PARAM);
        }

        $college = $this->collegeService->getCollegeById($data['college_id']);
        if(!$college || $college->school->school_id != $data['school_id']){
            throw new APIException('非法操作', APIException::ILLGAL_OPERATION);
        }

        $data['professor_full_name'] = $data['professor_fisrt_name'].$data['professor_second_name'];
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
}
