<?php

namespace App\Http\Controllers;

use App\Professor;
use App\Service\Abstracts\ProfessorServiceAbstract;
use App\Service\Abstracts\SchoolServiceAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfessorController extends Controller
{
    //

    protected $professorService;
    protected $schoolService;

    public function __construct(
      ProfessorServiceAbstract $professorService,
      SchoolServiceAbstract $schoolService
    ) {
        $this->professorService = $professorService;
        $this->schoolService = $schoolService;
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
        if($request->isMethod('POST')){

            $data = $request->all();
            $validator = $this->professorService->validatorForCreate($data);
            if($validator !== true){
                return redirect(route('backend.professor.add.get'))
                  ->withErrors($validator);
            }
            $data['professor_full_name'] = $data['professor_fisrt_name'].$data['professor_second_name'];
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
}
