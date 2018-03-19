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

    public function refreshAccessToken()
    {
        $client = new \GuzzleHttp\Client([
          'base_uri' => env('UCENTER_URL')
        ]);
        $response = $client->request('POST', env('UCENTER_URL') . 'oauth/access_token', [
          'form_params' => [
            "client_id" => env('CLIENT_ID'),
            "client_secret" => env('CLIENT_SECRET'),
            "grant_type" => "refresh_token",
            "refresh_token" => $GLOBALS['gStudent']->refresh_token,
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
            $this->updateStudent($GLOBALS['gStudent'], $arr);
        }
    }


    public function setPoints($delta)
    {
        a:
        try {
            $client = new \GuzzleHttp\Client([
              'base_uri' => env('UCENTER_URL')
            ]);
            $response = $client->request('PUT', env('UCENTER_URL') . self::SET_POINTS_URL, [
              'json' => [
                'access_token' => $GLOBALS['gStudent']->access_token,
                'delta' => $delta,
              ]
            ]);
            $content = $response->getBody()->getContents();
            $pointsInfo = json_decode($content, true);
            if ($pointsInfo['success']) {
                return $pointsInfo['entities'];
            }

        } catch (ClientException $exception) {
            if ($exception->getCode() == 401) {
                $this->refreshAccessToken();
                goto a;
            }
        }

    }

    public function getPoints()
    {
        a:
        try {
            $client = new \GuzzleHttp\Client([
              'base_uri' => env('UCENTER_URL')
            ]);
            $response = $client->request('GET', env('UCENTER_URL') . self::GET_POINTS_URL, [
              'query' => [
                'access_token' => $GLOBALS['gStudent']->access_token
              ]
            ]);
            $content = $response->getBody()->getContents();
            $pointsInfo = json_decode($content, true);
            if ($pointsInfo['success']) {
                return $pointsInfo['entities']['points'];
            }
        } catch (ClientException $exception) {
            if ($exception->getCode() == 401) {
                $this->refreshAccessToken();
                goto a;
            }
        }

    }


}