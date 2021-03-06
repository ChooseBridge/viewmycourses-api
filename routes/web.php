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
    Route::get('admin/country/update', ['uses' => 'CountryController@updateCountry', 'as' => 'country.update.get']);
    Route::post('admin/country/update', ['uses' => 'CountryController@updateCountry', 'as' => 'country.update.post']);
    Route::post('admin/country/delete', ['uses' => 'CountryController@deleteCountry', 'as' => 'country.delete.post']);

    Route::get('admin/province/index', ['uses' => 'ProvinceController@index', 'as' => 'province.index']);
    Route::get('admin/province/add', ['uses' => 'ProvinceController@addProvince', 'as' => 'province.add.get']);
    Route::post('admin/province/add', ['uses' => 'ProvinceController@addProvince', 'as' => 'province.add.post']);
    Route::get('admin/province/update', ['uses' => 'ProvinceController@updateProvince', 'as' => 'province.update.get']);
    Route::post('admin/province/update', ['uses' => 'ProvinceController@updateProvince', 'as' => 'province.update.post']);
    Route::post('admin/province/delete', ['uses' => 'ProvinceController@deleteProvince', 'as' => 'province.delete.post']);

    Route::get('admin/city/index', ['uses' => 'CityController@index', 'as' => 'city.index']);
    Route::get('admin/city/add', ['uses' => 'CityController@addCity', 'as' => 'city.add.get']);
    Route::post('admin/city/add', ['uses' => 'CityController@addCity', 'as' => 'city.add.post']);
    Route::get('admin/city/update', ['uses' => 'CityController@updateCity', 'as' => 'city.update.get']);
    Route::post('admin/city/update', ['uses' => 'CityController@updateCity', 'as' => 'city.update.post']);
    Route::post('admin/city/delete', ['uses' => 'CityController@deleteCity', 'as' => 'city.delete.post']);

    Route::get('admin/school/index', ['uses' => 'SchoolController@index', 'as' => 'school.index']);
    Route::get('admin/school/add', ['uses' => 'SchoolController@addSchool', 'as' => 'school.add.get']);
    Route::post('admin/school/add', ['uses' => 'SchoolController@addSchool', 'as' => 'school.add.post']);
    Route::get('admin/school/approve', ['uses' => 'SchoolController@approve', 'as' => 'school.aprove.get']);
    Route::get('admin/school/reject', ['uses' => 'SchoolController@reject', 'as' => 'school.reject.get']);
    Route::get('admin/school/update', ['uses' => 'SchoolController@updateSchool', 'as' => 'school.update.get']);
    Route::post('admin/school/update', ['uses' => 'SchoolController@updateSchool', 'as' => 'school.update.post']);
    Route::get('admin/school/comment', ['uses' => 'SchoolController@showComment', 'as' => 'school.show-comment.get']);
    Route::get('admin/school-comment/index', ['uses' => 'SchoolController@commentIndex', 'as' => 'school-comment.index']);



    Route::get('admin/course-category/index', ['uses' => 'CourseCategoryController@index', 'as' => 'course-category.index']);
    Route::get('admin/course-category.add.get', ['uses' => 'CourseCategoryController@addCategory', 'as' => 'course-category.add.get']);
    Route::post('admin/course-category.add.post', ['uses' => 'CourseCategoryController@addCategory', 'as' => 'course-category.add.post']);
    Route::get('admin/course-category.update.get', ['uses' => 'CourseCategoryController@updateCategory', 'as' => 'course-category.update.get']);
    Route::post('admin/course-category.update.post', ['uses' => 'CourseCategoryController@updateCategory', 'as' => 'course-category.update.post']);

    Route::get('admin/course/index', ['uses' => 'CourseController@index', 'as' => 'course.index']);
    Route::get('admin/course.add.get', ['uses' => 'CourseController@addCourse', 'as' => 'course.add.get']);
    Route::post('admin/course.add.post', ['uses' => 'CourseController@addCourse', 'as' => 'course.add.post']);
    Route::get('admin/course.update.get', ['uses' => 'CourseController@updateCourse', 'as' => 'course.update.get']);
    Route::post('admin/course.update.post', ['uses' => 'CourseController@updateCourse', 'as' => 'course.update.post']);

    Route::get('admin/college/index', ['uses' => 'CollegeController@index', 'as' => 'college.index']);
    Route::get('admin/college/add', ['uses' => 'CollegeController@addCollege', 'as' => 'college.add.get']);
    Route::post('admin/college/add', ['uses' => 'CollegeController@addCollege', 'as' => 'college.add.post']);
    Route::get('admin/college/update', ['uses' => 'CollegeController@updateCollege', 'as' => 'college.update.get']);
    Route::post('admin/college/update', ['uses' => 'CollegeController@updateCollege', 'as' => 'college.update.post']);


    Route::get('admin/school-district/index', ['uses' => 'SchoolDistrictController@index', 'as' => 'district.index']);
    Route::get('admin/school-district/add',
      ['uses' => 'SchoolDistrictController@addDistrict', 'as' => 'district.add.get']);
    Route::post('admin/school-district/add',
      ['uses' => 'SchoolDistrictController@addDistrict', 'as' => 'district.add.post']);
    Route::get('admin/school-district/update',
      ['uses' => 'SchoolDistrictController@updateDistrict', 'as' => 'district.update.get']);
    Route::post('admin/school-district/update',
      ['uses' => 'SchoolDistrictController@updateDistrict', 'as' => 'district.update.post']);

    Route::get('admin/professor/index', ['uses' => 'ProfessorController@index', 'as' => 'professor.index']);
    Route::get('admin/professor/add', ['uses' => 'ProfessorController@addProfessor', 'as' => 'professor.add.get']);
    Route::post('admin/professor/add', ['uses' => 'ProfessorController@addProfessor', 'as' => 'professor.add.post']);
    Route::get('admin/professor/approve', ['uses' => 'ProfessorController@approve', 'as' => 'professor.aprove.get']);
    Route::get('admin/professor/reject', ['uses' => 'ProfessorController@reject', 'as' => 'professor.reject.get']);
    Route::get('admin/professor/update', ['uses' => 'ProfessorController@updateProfessor', 'as' => 'professor.update.get']);
    Route::post('admin/professor/update', ['uses' => 'ProfessorController@updateProfessor', 'as' => 'professor.update.post']);
    Route::get('admin/professor/comment', ['uses' => 'ProfessorController@showComment', 'as' => 'professor.show-comment.get']);
    Route::get('admin/professor-comment/index', ['uses' => 'ProfessorController@commentIndex', 'as' => 'professor-comment.index']);

    Route::get('admin/professor-rate/index',
      ['uses' => 'ProfessorRateController@Index', 'as' => 'professor-rate.index']);
    Route::get('admin/professor-rate/approve',
      ['uses' => 'ProfessorRateController@approve', 'as' => 'professor-rate.aprove.get']);
    Route::get('admin/professor-rate/reject',
      ['uses' => 'ProfessorRateController@reject', 'as' => 'professor-rate.reject.get']);
    Route::get('admin/professor-rate/detail',
      ['uses' => 'ProfessorRateController@detail', 'as' => 'professor-rate.detail']);
    Route::get('admin/professor-rate/delete',
      ['uses' => 'ProfessorRateController@delete', 'as' => 'professor-rate.delete']);
    Route::get('admin/professor-rate/update',
      ['uses' => 'ProfessorRateController@update', 'as' => 'professor-rate.update.get']);
    Route::post('admin/professor-rate/update',
      ['uses' => 'ProfessorRateController@update', 'as' => 'professor-rate.update.post']);


    Route::get('admin/school-rate/index', ['uses' => 'SchoolRateController@Index', 'as' => 'school-rate.index']);
    Route::get('admin/school-rate/approve',
      ['uses' => 'SchoolRateController@approve', 'as' => 'school-rate.aprove.get']);
    Route::get('admin/school-rate/reject', ['uses' => 'SchoolRateController@reject', 'as' => 'school-rate.reject.get']);
    Route::get('admin/school-rate/detail', ['uses' => 'SchoolRateController@detail', 'as' => 'school-rate.detail']);
    Route::get('admin/school-rate/delete', ['uses' => 'SchoolRateController@delete', 'as' => 'school-rate.delete']);


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
                      'gender' => $userInfo['entities'][0]['profile']['gender'],
                      'education_status' => $userInfo['entities'][0]['profile']['education_status'],

                    ];

                    if (isset($userInfo['entities'][0]['academic']) && $userInfo['entities'][0]['academic']) {

                        $arr['is_graduate'] = $userInfo['entities'][0]['academic']['status'];
                        $arr['graduate_year'] = $userInfo['entities'][0]['academic']['graduate_year'];
                        $arr['school_name'] = $userInfo['entities'][0]['academic']['school_name'];
                        $arr['major'] = $userInfo['entities'][0]['academic']['major'];
                        $arr['exam_province'] = $userInfo['entities'][0]['academic']['exam_province'];
                    }

                    $arr['vip_expire_time'] = $userInfo['entities'][0]['vip_expire'];
                    if (\Carbon\Carbon::parse($userInfo['entities'][0]['vip_expire'])->gte(\Carbon\Carbon::now())) {
                        $arr['is_vip'] = 1;
                    } else {
                        $arr['is_vip'] = 0;
                    }


                    $student = $studentService->createStudent($arr);
                    if ($student) {
                        return Redirect::to($redirectUrl . "?token=" . $student->token, 301);
                    }


                } else {
                    //老用户

                    $arr = [
                      'email' => $userInfo['entities'][0]['email'],
                      'token' => md5(uniqid()),
                      'token_expires_time' => date("Y-m-d H:i:s", strtotime("+1 day")),
                      'access_token' => $tokenInfo['access_token'],
                      'refresh_token' => $tokenInfo['refresh_token'],
                      'access_token_expires_time' => date("Y-m-d H:i:s", time() + $tokenInfo['expires_in']),
                      'gender' => $userInfo['entities'][0]['profile']['gender'],
                      'education_status' => $userInfo['entities'][0]['profile']['education_status'],
                      'mobile' => (string)$userInfo['entities'][0]['profile']['mobile'],
                      'mobile_verified' => (string)$userInfo['entities'][0]['profile']['mobile_verified'],
                      'email_verified' => (string)$userInfo['entities'][0]['profile']['email_verified'],
                      'is_email_edu' => (int)$userInfo['entities'][0]['profile']['is_email_edu'],

                    ];

                    if (isset($userInfo['entities'][0]['academic']) && $userInfo['entities'][0]['academic']) {

                        $arr['is_graduate'] = $userInfo['entities'][0]['academic']['status'];
                        $arr['graduate_year'] = $userInfo['entities'][0]['academic']['graduate_year'];
                        $arr['school_name'] = $userInfo['entities'][0]['academic']['school_name'];
                        $arr['major'] = $userInfo['entities'][0]['academic']['major'];
                        $arr['exam_province'] = $userInfo['entities'][0]['academic']['exam_province'];
                    }


                    $arr['vip_expire_time'] = $userInfo['entities'][0]['vip_expire'];
                    if (\Carbon\Carbon::parse($userInfo['entities'][0]['vip_expire'])->gte(\Carbon\Carbon::now())) {
                        $arr['is_vip'] = 1;
                    } else {
                        $arr['is_vip'] = 0;
                    }



                    $isUpdate = $studentService->updateStudent($student, $arr);
                    if ($isUpdate) {
                        if (strpos($redirectUrl, "?") === false) {
                            return Redirect::to($redirectUrl . "?token=" . $student->token, 301);
                        } else {
                            return Redirect::to($redirectUrl . "&token=" . $student->token, 301);
                        }
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

    Route::get('set-vip-time',
      ['uses' => 'InternalController@setVipTime', 'as' => 'internal.set-vip-time']);
    Route::get('get-vip-time',
      ['uses' => 'InternalController@getVipTime', 'as' => 'internal.get-vip-time']);

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
    Route::get('get-student-by-id', ['uses' => 'StudentController@getStudentById', 'as' => 'api.get-student-by-id']);
    Route::get('get-student-message',
      ['uses' => 'StudentController@getStudentMessage', 'as' => 'api.get-student-message']);
    Route::get('get-student-unread-count',
      ['uses' => 'StudentController@getUnReadCount', 'as' => 'api.get-student-unread-count']);

    Route::get('test-set-ponits', ['uses' => 'StudentController@setPoints', 'as' => 'api.test-set-ponits']);
    Route::get('test-get-ponits', ['uses' => 'StudentController@getPoints', 'as' => 'api.test-get-ponits']);

//
    Route::get('get-school-by-condition',
      ['uses' => 'SchoolController@getSchoolByCondition', 'as' => 'api.get-school-by-condition']);
    Route::get('get-all-school-by-name',
      ['uses' => 'SchoolController@getAllSchoolByName', 'as' => 'api.get-all-school-by-name']);
    Route::get('get-professor-by-condition',
      ['uses' => 'ProfessorController@getProfessorByCondition', 'as' => 'api.get-professor-by-condition']);


    Route::get('get-all-by-name',
      ['uses' => 'StudentController@getAllByName', 'as' => 'api.get-all-by-name']);


    Route::get('thumbs-up-professor',
      ['uses' => 'ProfessorController@thumbsUpProfessor', 'as' => 'api.thumbs-up-professor']);
    Route::get('thumbs-up-school',
      ['uses' => 'SchoolController@thumbsUpSchool', 'as' => 'api.thumbs-up-school']);
    Route::get('thumbs-up-professor-rate',
      ['uses' => 'ProfessorRateController@thumbsUpRate', 'as' => 'api.thumbs-up-professor-rate']);
    Route::get('thumbs-down-professor-rate',
      ['uses' => 'ProfessorRateController@thumbsDownRate', 'as' => 'api.thumbs-down-professor-rate']);
    Route::get('thumbs-up-school-rate',
      ['uses' => 'SchoolRateController@thumbsUpRate', 'as' => 'api.thumbs-up-school-rate']);
    Route::get('thumbs-down-school-rate',
      ['uses' => 'SchoolRateController@thumbsDownRate', 'as' => 'api.thumbs-down-school-rate']);

    Route::post('create-school-comment',
      ['uses' => 'SchoolController@createComment', 'as' => 'api.create-school-comment']);
    Route::post('create-professor-comment',
      ['uses' => 'ProfessorController@createComment', 'as' => 'api.create-professor-comment']);


});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::middleware('wechat_sign')->prefix('api/wechat')->group(function () {
    Route::post('login', 'WechatController@login');
    Route::post('vip', 'WechatController@getUserVIP');
});