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
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;


class StudentServiceConcrete implements StudentServiceAbstract
{
    const GET_POINTS_URL = "api/user/points/get";
    const SET_POINTS_URL = "api/user/points/set";

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

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

    public function refreshAccessToken($student)
    {
        $client = new \GuzzleHttp\Client([
          'base_uri' => env('UCENTER_URL')
        ]);
        $response = $client->request('POST', env('UCENTER_URL') . 'oauth/access_token', [
          'form_params' => [
            "client_id" => env('CLIENT_ID'),
            "client_secret" => env('CLIENT_SECRET'),
            "grant_type" => "refresh_token",
            "refresh_token" => $student->refresh_token,
          ]
        ]);
        $body = $response->getBody();
        $tokenInfo = json_decode($body, true);
        if (!empty($tokenInfo) && isset($tokenInfo['access_token'])) {
            $arr = [
              'access_token' => $tokenInfo['access_token'],
              'refresh_token' => $tokenInfo['refresh_token'],
              'access_token_expires_time' => date("Y-m-d H:i:s", time() + $tokenInfo['expires_in']),
            ];
            $this->updateStudent($student, $arr);
        }
    }


    public function setPoints($delta, $comment, $student)
    {
        a:
        try {
            $client = new \GuzzleHttp\Client([
              'base_uri' => env('UCENTER_URL')
            ]);
            $response = $client->request('PUT', env('UCENTER_URL') . self::SET_POINTS_URL, [
              'headers' => [
                'Authorization' => "Bearer " . $student->access_token,
              ],
              'json' => [
                'comment' => $comment,
                'delta' => $delta,
              ]
            ]);
            $content = $response->getBody()->getContents();
            $pointsInfo = json_decode($content, true);

            return $pointsInfo['success'];

        } catch (ClientException $exception) {
            if ($exception->getCode() == 401) {
                $this->refreshAccessToken($student);
                goto a;
            }
        }

    }

    public function getPoints($student)
    {
        a:
        try {
            $client = new \GuzzleHttp\Client([
              'base_uri' => env('UCENTER_URL')
            ]);
            $response = $client->request('GET', env('UCENTER_URL') . self::GET_POINTS_URL, [
              'headers' => [
                'Authorization' => "Bearer " . $student->access_token
              ]
            ]);
            $content = $response->getBody()->getContents();
            $pointsInfo = json_decode($content, true);
            if ($pointsInfo['success']) {
                return $pointsInfo['entities']['points'];
            }
        } catch (ClientException $exception) {
            if ($exception->getCode() == 401) {
                $this->refreshAccessToken($student);
                goto a;
            }
        }

    }

    public function getCurrentStudent()
    {
        if (isset($GLOBALS['gStudent'])) {
            return $GLOBALS['gStudent'];
        }
        $token = $this->request->header('token');
        $student = $this->getStudentByToken($token);
        if ($student) {
            $GLOBALS['gStudent'] = $student;
            return $student;
        }
        return null;
    }

    public function currentStudentIsVip()
    {
        $student = $this->getCurrentStudent();
        if ($student) {
            return false;
        }
        return $student->is_vip == 1 ? true : false;
    }


}