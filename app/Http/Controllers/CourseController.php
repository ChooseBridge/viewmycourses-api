<?php

namespace App\Http\Controllers;

use App\Service\Abstracts\CourseServiceAbstract;
use App\Service\Abstracts\ProfessorServiceAbstract;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    public $courseService;
    public $professorService;


    public function __construct(
      CourseServiceAbstract $courseService,
      ProfessorServiceAbstract $professorService
    ) {
        $this->courseService = $courseService;
        $this->professorService = $professorService;
    }

    public function index()
    {

        $courses = $this->courseService->getCourseForPage();
        return view('course.index', [
          'courses' => $courses
        ]);

    }


    public function addCourse(Request $request)
    {
        if ($request->isMethod('POST')) {

            $data = $request->all();
            $validator = $this->courseService->validatorForCreate($data);
            if ($validator !== true) {
                return redirect(route('backend.course.add.get'))
                  ->withErrors($validator);
            }
            $this->courseService->createCourse($data);
            return redirect(route("backend.course.index"));

        }

        $professors = $this->professorService->getAllCheckedProfessors();
        return view('course.add', [
          'professors' => $professors
        ]);
    }

    public function updateCourse(Request $request)
    {

        $courseId = $request->get('course_id');
        $course = $this->courseService->getCourseById($courseId);
        if (!$courseId || !$course) {
            return redirect(route("backend.course.index"));
        }
        if ($request->isMethod('POST')) {
            $data = $request->all();
            $course->update($data);
            return redirect(route("backend.course.index"));
        }

        $professors = $this->professorService->getAllCheckedProfessors();
        return view('course.update', [
          'professors' => $professors,
          'course' => $course,
        ]);

    }

}
