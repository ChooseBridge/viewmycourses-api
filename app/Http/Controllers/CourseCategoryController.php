<?php

namespace App\Http\Controllers;

use App\Service\Abstracts\SchoolCourseCategoryServiceAbstract;
use App\Service\Abstracts\SchoolServiceAbstract;
use Illuminate\Http\Request;

class CourseCategoryController extends Controller
{
    //

    protected $schoolCourseCategoryService;
    protected $schoolService;

    public function __construct(
      SchoolCourseCategoryServiceAbstract $schoolCourseCategoryService,
      SchoolServiceAbstract $schoolService
    ) {
        $this->schoolCourseCategoryService = $schoolCourseCategoryService;
        $this->schoolService = $schoolService;
    }

    public function index()
    {

        $courseCategorys = $this->schoolCourseCategoryService->getCourseCategoryForPage();
        return view('course_category.index', [
          'courseCategorys' => $courseCategorys
        ]);

    }


    public function addCategory(Request $request)
    {
        if ($request->isMethod('POST')) {

            $data = $request->all();
            $validator = $this->schoolCourseCategoryService->validatorForCreate($data);
            if ($validator !== true) {
                return redirect(route('backend.course-category.add.get'))
                  ->withErrors($validator);
            }
            $this->schoolCourseCategoryService->createCourseCategory($data);
            return redirect(route("backend.course-category.index"));

        }

        $schools = $this->schoolService->getAllCheckedSchools();
        return view('course_category.add', [
          'schools' => $schools
        ]);
    }


    public function updateCategory(Request $request)
    {
        $courseCategoryId = $request->get('course_category_id');
        $courseCategory = $this->schoolCourseCategoryService->getCourseCategoryById($courseCategoryId);
        if (!$courseCategoryId || !$courseCategory) {
            return redirect(route("backend.course-category.index"));
        }
        if ($request->isMethod('POST')) {
            $data = $request->all();
            $courseCategory->update($data);
            return redirect(route("backend.course-category.index"));
        }

        $schools = $this->schoolService->getAllCheckedSchools();
        return view('course_category.update', [
          'schools' => $schools,
          'courseCategory' => $courseCategory,
        ]);
    }


}
