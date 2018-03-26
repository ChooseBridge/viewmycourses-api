<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/15
 * Time: 21:19
 */

namespace App\Service\Concretes;


use App\School;
use App\Service\Abstracts\MessageServiceAbstract;
use App\Service\Abstracts\SchoolServiceAbstract;
use Illuminate\Support\Facades\Validator;

class SchoolServiceConcrete implements SchoolServiceAbstract
{

    protected $messageService;

    public function __construct(MessageServiceAbstract $messageService)
    {
        $this->messageService = $messageService;
    }

    public function createSchool($data)
    {
        $school = School::create($data);
        return $school;
    }

    public function validatorForCreate($data)
    {
        $validator = Validator::make($data, [
          'school_name' => 'required|unique:school|max:255',
          'school_nick_name' => 'required|max:255',
          'country_id' => 'required|integer',
          'province_id' => 'required|integer',
          'city_id' => 'required|integer',
          'website_url' => 'required|max:255',
          'your_email' => 'required|email',
        ]);
        return $validator->fails() ? $validator : true;

    }

    public function approveSchoolById($id)
    {
        $school = $this->getSchoolById($id);
        if ($school) {
            $school->check_status = School::APPROVE_CHECK;
            $isApprove = $school->save();
            if ($isApprove) {
                $content = "您创建的学校" . $school->school_name . "审核通过";
                $student_id = $school->create_student_id;
                $messageContent = [
                    'message'=>$content,
                    'type'=>'success',
                    'info_type'=>'school',
                    'id'=>$school->school_id,
                    'name'=>$school->school_name,
                ];
                $data = [
                  'message_content' => json_encode($messageContent),
                  'to_student_id' => $student_id
                ];
                $this->messageService->createMessage($data);
            }
        }
    }

    public function rejectSchoolById($id)
    {
        $school = $this->getSchoolById($id);
        if ($school) {
            $content = "您创建的学校" . $school->school_name . "审核失败";
            $student_id = $school->create_student_id;
            $isReject = $school->delete();
            if($isReject){
                $messageContent = [
                  'message'=>$content,
                  'type'=>'fail',
                  'info_type'=>'school',
                  'id'=>$school->school_id,
                  'name'=>$school->school_name,
                ];
                $data = [
                  'message_content' => json_encode($messageContent),
                  'to_student_id' => $student_id
                ];
                $this->messageService->createMessage($data);
            }
        }
    }

    public function getSchoolsForPage($limit = 10, $queryCallBack = null)
    {
        if ($queryCallBack) {
            $schools = School::where($queryCallBack)->paginate($limit);
        } else {
            $schools = School::paginate($limit);
        }

        return $schools;
    }

    public function getAllCheckedSchoolsForPage($limit, $queryCallBack)
    {
        $bulider = School::where('check_status', School::APPROVE_CHECK);
        if ($queryCallBack) {
            $bulider->where($queryCallBack);
        }
        $schools = $bulider->paginate($limit);
        return $schools;
    }

    public function getSchoolById($id)
    {
        $school = School::where('school_id', $id)->first();
        return $school;
    }

    public function getSchoolByName($name)
    {
        $school = School::where('school_name', $name)->first();
        return $school;
    }

    public function getAllSchools($queryCallBack = null)
    {
        if ($queryCallBack) {
            $schools = School::where($queryCallBack)->get();
        } else {
            $schools = School::All();
        }

        return $schools;
    }

    public function getAllCheckedSchools($queryCallBack = null)
    {

        $bulider = School::where('check_status', School::APPROVE_CHECK);
        if ($queryCallBack) {
            $bulider->where($queryCallBack);
        }
        $schools = $bulider->get();
        return $schools;
    }

    public function getAllCheckedSchoolsGroupCountry()
    {
        $schools = $this->getAllCheckedSchools();
        $res = [];
        foreach ($schools as $school) {
            $res[$school->country->country_name][] = [
              'school_id' => $school->school_id,
              'school_name' => $school->school_name,
              'school_nick_name' => $school->school_nick_name,
            ];
        }
        return $res;
    }

    public function isCheckedById($id)
    {
        $school = $this->getSchoolById($id);
        if ($school && $school->check_status == School::APPROVE_CHECK) {
            return true;
        }
        return false;
    }
}