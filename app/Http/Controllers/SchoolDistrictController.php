<?php

namespace App\Http\Controllers;

use App\Service\Abstracts\SchoolDistrictServiceAbstract;
use App\Service\Abstracts\SchoolServiceAbstract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolDistrictController extends Controller
{
    protected $schoolDistrictService;
    protected $schoolService;

    public function __construct(
      SchoolDistrictServiceAbstract $schoolDistrictService,
      SchoolServiceAbstract $schoolService
    ) {
        $this->schoolDistrictService = $schoolDistrictService;
        $this->schoolService = $schoolService;
    }


// backend
    public function index()
    {

        $districts = $this->schoolDistrictService->getDistrictsForPage();
        return view('district.index', [
          'districts' => $districts
        ]);
    }

    public function addDistrict(Request $request)
    {
        if ($request->isMethod('POST')) {

            $data = $request->all();
            $validator = $this->schoolDistrictService->validatorForCreate($data);
            if ($validator !== true) {
                return redirect(route('backend.district.add.get'))
                  ->withErrors($validator);
            }
            $data['create_user_id'] = Auth::user()->id;
            $this->schoolDistrictService->createDistrict($data);
            return redirect(route("backend.district.index"));

        }

        $schools = $this->schoolService->getAllCheckedSchoolsGroupCountry();
        return view('district.add', [
          'schools' => $schools
        ]);
    }


    public function updateDistrict(Request $request)
    {

        $districtId = $request->get('school_district_id');
        $district = $this->schoolDistrictService->getDistrictById($districtId);
        if (!$district || !$districtId) {
            return redirect(route("backend.district.index"));
        }
        if ($request->isMethod('POST')) {

            $data = $request->all();
            $district->update($data);
            return redirect(route("backend.district.index"));
        }

        $schools = $this->schoolService->getAllCheckedSchoolsGroupCountry();
        return view('district.update', [
          'schools' => $schools,
          'district' => $district,
        ]);


    }

}
