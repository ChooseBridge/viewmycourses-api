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

Route::group(['middleware' => 'admin.user', 'as' => 'backend.'], function () {

    Route::get('admin/country/index', ['uses' => 'CountryController@index', 'as' => 'country.index']);
    Route::get('admin/country/add', ['uses' => 'CountryController@addCountry', 'as' => 'country.add.get']);
    Route::post('admin/country/add', ['uses' => 'CountryController@addCountry', 'as' => 'country.add.post']);

    Route::get('admin/province/index', ['uses' => 'ProvinceController@index', 'as' => 'province.index']);
    Route::get('admin/province/add', ['uses' => 'ProvinceController@addProvince', 'as' => 'province.add.get']);
    Route::post('admin/province/add', ['uses' => 'ProvinceController@addProvince', 'as' => 'province.add.post']);

    Route::get('admin/city/index', ['uses' => 'CityController@index', 'as' => 'city.index']);
    Route::get('admin/city/add', ['uses' => 'CityController@addCity', 'as' => 'city.add.get']);
    Route::post('admin/city/add', ['uses' => 'CityController@addCity', 'as' => 'city.add.post']);

    Route::get('admin/school/index', ['uses' => 'SchoolController@index', 'as' => 'school.index']);
    Route::get('admin/school/add', ['uses' => 'SchoolController@addSchool', 'as' => 'school.add.get']);
    Route::post('admin/school/add', ['uses' => 'SchoolController@addSchool', 'as' => 'school.add.post']);
    Route::get('admin/school/approve', ['uses' => 'SchoolController@approve', 'as' => 'school.aprove.get']);
    Route::get('admin/school/reject', ['uses' => 'SchoolController@reject', 'as' => 'school.reject.get']);


    Route::get('admin/college/index', ['uses' => 'CollegeController@index', 'as' => 'college.index']);
    Route::get('admin/college/add', ['uses' => 'CollegeController@addCollege', 'as' => 'college.add.get']);
    Route::post('admin/college/add', ['uses' => 'CollegeController@addCollege', 'as' => 'college.add.post']);


    Route::get('admin/school-district/index', ['uses' => 'SchoolDistrictController@index', 'as' => 'district.index']);
    Route::get('admin/school-district/add',
      ['uses' => 'SchoolDistrictController@addDistrict', 'as' => 'district.add.get']);
    Route::post('admin/school-district/add',
      ['uses' => 'SchoolDistrictController@addDistrict', 'as' => 'district.add.post']);

    Route::get('admin/professor/index', ['uses' => 'ProfessorController@index', 'as' => 'professor.index']);
    Route::get('admin/professor/add', ['uses' => 'ProfessorController@addProfessor', 'as' => 'professor.add.get']);
    Route::post('admin/professor/add', ['uses' => 'ProfessorController@addProfessor', 'as' => 'professor.add.post']);
    Route::get('admin/professor/approve', ['uses' => 'ProfessorController@approve', 'as' => 'professor.aprove.get']);
    Route::get('admin/professor/reject', ['uses' => 'ProfessorController@reject', 'as' => 'professor.reject.get']);

    Route::get('admin/professor-rate/index',
      ['uses' => 'ProfessorRateController@Index', 'as' => 'professor-rate.index']);
    Route::get('admin/professor-rate/approve',
      ['uses' => 'ProfessorRateController@approve', 'as' => 'professor-rate.aprove.get']);
    Route::get('admin/professor-rate/reject',
      ['uses' => 'ProfessorRateController@reject', 'as' => 'professor-rate.reject.get']);
    Route::get('admin/professor-rate/detail',
      ['uses' => 'ProfessorRateController@detail', 'as' => 'professor-rate.detail']);


    Route::get('admin/school-rate/index', ['uses' => 'SchoolRateController@Index', 'as' => 'school-rate.index']);
    Route::get('admin/school-rate/approve',
      ['uses' => 'SchoolRateController@approve', 'as' => 'school-rate.aprove.get']);
    Route::get('admin/school-rate/reject', ['uses' => 'SchoolRateController@reject', 'as' => 'school-rate.reject.get']);
    Route::get('admin/school-rate/detail', ['uses' => 'SchoolRateController@detail', 'as' => 'school-rate.detail']);


    Route::post('admin/api/get-college-by-school',
      ['uses' => 'CollegeController@getCollegeBySchool', 'as' => 'get-college-by-school']);

});

Route::get('/callback', function (\App\Service\Abstracts\StudentServiceAbstract $studentService) {

    $code = $_GET['code'];
    $redirectUrl = isset($_GET['state']) ? $_GET['state'] : "";

    if ($code) {

        $client = new \GuzzleHttp\Client([
          'base_uri' => env('UCENTER_URL')
        ]);
        $response = $client->request('POST', env('UCENTER_URL') . 'oauth/token', [
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
              env('UCENTER_URL') . '/api/user/basic', [
                'headers' => [
                  'Authorization' => "Bearer " . $tokenInfo['access_token'],
                ]
              ]);
            $content = $response->getBody()->getContents();
            $userInfo = json_decode($content, true);


            if (!empty($userInfo) && $userInfo['success']) {

                $ucenterId = $userInfo['entities'][0]['id'];
                $student = $studentService->getStudentByUCenterUId($ucenterId);

                //新用户
                if (!$student) {

                    $arr = [
                      'name' => $userInfo['entities'][0]['name'],
                      'email' => $userInfo['entities'][0]['email'],
                      'password' => '',
                      'token' => md5(uniqid()),
                      'token_expires_time' => date("Y-m-d H:i:s", strtotime("+1 day")),
                      'access_token' => $tokenInfo['access_token'],
                      'refresh_token' => $tokenInfo['refresh_token'],
                      'access_token_expires_time' => date("Y-m-d H:i:s", time() + $tokenInfo['expires_in']),
                      'ucenter_uid' => $ucenterId,
                      'mobile' => (string)$userInfo['entities'][0]['profile']['mobile'],
                      'mobile_verified' => $userInfo['entities'][0]['profile']['mobile_verified'],
                      'email_verified' => $userInfo['entities'][0]['profile']['email_verified'],
                      'is_email_edu' => (int)$userInfo['entities'][0]['profile']['is_email_edu'],

                    ];

                    //如果是edu邮箱并且验证了邮箱
                    if ($arr['is_email_edu'] && $arr['email_verified']) {
                        $arr['is_vip'] = 1;
                        $arr['vip_expire_time'] = date("Y-m-d H:i:s", strtotime("+6 month", time()));
                    }


                    $student = $studentService->createStudent($arr);
                    if ($student) {
                        return Redirect::to($redirectUrl . "?token=" . $student->token, 301);
                    }


                } else {
                    //老用户

                    $arr = [
                      'token' => md5(uniqid()),
                      'token_expires_time' => date("Y-m-d H:i:s", strtotime("+1 day")),
                      'access_token' => $tokenInfo['access_token'],
                      'refresh_token' => $tokenInfo['refresh_token'],
                      'access_token_expires_time' => date("Y-m-d H:i:s", time() + $tokenInfo['expires_in']),

                    ];

                    //说明之前没有验证邮箱 现在验证了
                    if ($student->email_verified == 0 && $userInfo['entities'][0]['profile']['email_verified']) {

                        //如果是edu邮箱 并且第一次为vip
                        if ($student->is_email_edu && $student->vip_expire_time == '1970-01-01 00:00:00') {
                            $arr['is_vip'] = 1;
                            $arr['vip_expire_time'] = date("Y-m-d H:i:s", strtotime("+6 month", time()));
                            $arr['email_verified'] = $userInfo['entities'][0]['profile']['email_verified'];
                        } elseif ($student->is_email_edu) {
                            //有可能是微信登录的未验证的edu邮箱用户
                            $arr['is_vip'] = 1;
                            $arr['vip_expire_time'] = date("Y-m-d H:i:s",
                              strtotime("+5 month 2 week", strtotime($student->vip_expire_time)));
                            $arr['email_verified'] = $userInfo['entities'][0]['profile']['email_verified'];
                        } else {
                            $arr['email_verified'] = $userInfo['entities'][0]['profile']['email_verified'];
                        }

                        if ($student->mobile != (string)$userInfo['entities'][0]['profile']['mobile']) {
                            $arr['mobile'] = $userInfo['entities'][0]['profile']['mobile'];
                        }
                        if ($student->mobile_verified != $userInfo['entities'][0]['profile']['mobile_verified']) {
                            $arr['mobile_verified'] = $userInfo['entities'][0]['profile']['mobile_verified'];
                        }

                        //vip失效
                        if (time() > strtotime($student->vip_expire_time)) {
                            $arr['is_vip'] = 0;
                        }


                    }

                    $isUpdate = $studentService->updateStudent($student, $arr);
                    if ($isUpdate) {
                        return Redirect::to($redirectUrl . "?token=" . $student->token, 301);
                    }
                }


            }

        }


    }
});

Route::group(['prefix' => 'open-api'], function () {

    Route::post('geo/get-province-by-country',
      ['uses' => 'GeoController@getProvinceByCountry', 'as' => 'geo.get-province-by-country']);
    Route::get('geo/get-all-countrys', ['uses' => 'GeoController@getAllCountrys', 'as' => 'geo.get-all-countrys']);
    Route::post('geo/get-city-by-province',
      ['uses' => 'GeoController@getCityByProvince', 'as' => 'geo.get-city-by-province']);

    Route::get('get-professor-detail',
      ['uses' => 'ProfessorController@getProfessorDetail', 'as' => 'api.get-professor-detail']);
    Route::get('get-school-detail', ['uses' => 'SchoolController@getSchoolDetail', 'as' => 'api.get-school-detail']);


});

Route::group(['prefix' => 'internal', 'middleware' => [\App\Http\Middleware\InternalCheck::class]], function () {

    Route::get('get-all-school-by-name',
      ['uses' => 'InternalController@getAllSchoolByName', 'as' => 'internal.get-all-school-by-name']);

});


Route::group(['prefix' => 'api', 'middleware' => [\App\Http\Middleware\CheckLogin::class]], function () {

    Route::post('school/create', ['uses' => 'SchoolController@createSchool', 'as' => 'school.create']);
    Route::post('professor/create', ['uses' => 'ProfessorController@createProfessor', 'as' => 'professor.create']);
    Route::post('professor-rate/create',
      ['uses' => 'ProfessorRateController@createRate', 'as' => 'professor-rate.create']);
    Route::post('school-rate/create', ['uses' => 'SchoolRateController@createRate', 'as' => 'school-rate.create']);

    Route::post('get-college-by-school',
      ['uses' => 'CollegeController@getCollegeBySchool', 'as' => 'api.get-college-by-school']);
    Route::get('get-school-group-by-country',
      ['uses' => 'SchoolController@getAllcheckedSchoolByCountry', 'as' => 'api.get-school-group-by-country']);

    Route::get('get-student', ['uses' => 'StudentController@getStudent', 'as' => 'api.get-student']);
    Route::get('get-student-message',
      ['uses' => 'StudentController@getStudentMessage', 'as' => 'api.get-student-message']);

    Route::get('test-set-ponits', ['uses' => 'StudentController@setPoints', 'as' => 'api.test-set-ponits']);
    Route::get('test-get-ponits', ['uses' => 'StudentController@getPoints', 'as' => 'api.test-get-ponits']);

//
    Route::get('get-school-by-condition',
      ['uses' => 'SchoolController@getSchoolByCondition', 'as' => 'api.get-school-by-condition']);
    Route::get('get-all-school-by-name',
      ['uses' => 'SchoolController@getAllSchoolByName', 'as' => 'api.get-all-school-by-name']);
    Route::get('get-professor-by-condition',
      ['uses' => 'ProfessorController@getProfessorByCondition', 'as' => 'api.get-professor-by-condition']);

    Route::get('thumbs-up-professor',
      ['uses' => 'ProfessorController@thumbsUpProfessor', 'as' => 'api.thumbs-up-professor']);
    Route::get('thumbs-up-professor-rate',
      ['uses' => 'ProfessorRateController@thumbsUpRate', 'as' => 'api.thumbs-up-professor-rate']);
    Route::get('thumbs-down-professor-rate',
      ['uses' => 'ProfessorRateController@thumbsDownRate', 'as' => 'api.thumbs-down-professor-rate']);
    Route::get('thumbs-up-school-rate',
      ['uses' => 'SchoolRateController@thumbsUpRate', 'as' => 'api.thumbs-up-school-rate']);
    Route::get('thumbs-down-school-rate',
      ['uses' => 'SchoolRateController@thumbsDownRate', 'as' => 'api.thumbs-down-school-rate']);


});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
