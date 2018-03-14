<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/callback', function (\App\Service\Abstracts\StudentServiceAbstract $studentService) {

    $code = $_GET['code'];

    if ($code) {

        $client = new \GuzzleHttp\Client([
          'base_uri' => env('UCENTER_URL')
        ]);
        $response = $client->request('POST', env('UCENTER_URL') . 'oauth/access_token', [
          'form_params' => [
            "client_id" => env('CLIENT_ID'),
            "client_secret" => env('CLIENT_SECRET'),
            "redirect_uri" => env('CALL_BACK_URL'),
            "code" => $code,
            "grant_type" => "authorization_code",
          ]
        ]);
        $body = $response->getBody();
        $tokenInfo = json_decode($body, true);
        if (!empty($tokenInfo) && isset($tokenInfo['access_token'])) {
            $response = $client->request('GET',
              env('UCENTER_URL') . '/api/user/basic?access_token=' . $tokenInfo['access_token']);
            $content = $response->getBody()->getContents();
            $userInfo = json_decode($content, true);
            if (!empty($userInfo) && $userInfo['success']) {
                $ucenterId = $userInfo['entities'][0]['id'];
                $student = $studentService->getStudentByUCenterUId($ucenterId);
                if (!$student) {
                    $arr = [
                        'name'=>$userInfo['entities'][0]['name'],
                        'email'=>$userInfo['entities'][0]['email'],
                        'password'=>'',
                        'token'=>md5(uniqid()),
                        'token_expires_time'=>date("Y-m-d H:i:s",strtotime("+1 day")),
                        'access_token'=>$tokenInfo['access_token'],
                        'refresh_token'=>$tokenInfo['refresh_token'],
                        'access_token_expires_time'=>date("Y-m-d H:i:s",time()+$tokenInfo['expires_in']),
                        'ucenter_uid'=>$ucenterId
                    ];
                    $newStudent = $studentService->createStudent($arr);
                }else{
                    $arr = [
                      'token'=>md5(uniqid()),
                      'token_expires_time'=>date("Y-m-d H:i:s",strtotime("+1 day")),
                      'access_token'=>$tokenInfo['access_token'],
                      'refresh_token'=>$tokenInfo['refresh_token'],
                      'access_token_expires_time'=>date("Y-m-d H:i:s",time()+$tokenInfo['expires_in']),

                    ];
                    $newStudent = $studentService->updateStudent($arr);
                }

                if($newStudent){
                    echo  $newStudent->token;die;
                }

            }

        }


    }
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
