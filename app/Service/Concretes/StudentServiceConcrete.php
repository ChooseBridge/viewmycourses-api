<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/14
 * Time: 20:41
 */

namespace App\Service\Concretes;

use App\Service\Abstracts\StudentServiceAbstract;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;


class StudentServiceConcrete implements StudentServiceAbstract
{

    public function createStudent($data)
    {
        $student = Student::create($data);
        return $student;
    }

    public function updateStudent($student, $data)
    {
        $res = $student->update($data);
        return $res;

    }

    public function getStudentByUCenterUId($uid)
    {
        $student = Student::where('ucenter_uid', $uid)->first();
        return $student;
    }

    public function getStudentByToken($token)
    {
        $student = Student::where('token', $token)->first();
        return $student;
    }



}