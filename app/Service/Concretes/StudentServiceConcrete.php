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
    const GET_POINTS_URL  = "api/user/points/get";
    const SET_POINTS_URL  = "api/user/points/set";

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


    public function setPoints($delta)
    {
        $client = new \GuzzleHttp\Client([
          'base_uri' => env('UCENTER_URL')
        ]);
        $response = $client->request('PUT', env('UCENTER_URL') . self::SET_POINTS_URL, [
          'json'=>[
            'access_token'=>$GLOBALS['gStudent']->access_token,
            'delta'=>$delta,
          ]
        ]);
        $content = $response->getBody()->getContents();
        $pointsInfo = json_decode($content, true);
        if($pointsInfo['success']){
            return $pointsInfo['entities'];
        }
    }

    public function getPoints()
    {
        $client = new \GuzzleHttp\Client([
          'base_uri' => env('UCENTER_URL')
        ]);
        $response = $client->request('GET', env('UCENTER_URL') . self::GET_POINTS_URL, [
          'query'=>[
            'access_token'=>$GLOBALS['gStudent']->access_token
          ]
        ]);
        $content = $response->getBody()->getContents();
        $pointsInfo = json_decode($content, true);
        if($pointsInfo['success']){
            return $pointsInfo['entities']['points'];
        }
    }


}