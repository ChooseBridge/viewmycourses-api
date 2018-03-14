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

Route::get('/callback', function () {

    $code = $_GET['code'];

    if($code){

        $client = new \GuzzleHttp\Client([
          'base_uri' => env('UCENTER_URL')
        ]);
        $response = $client->request('POST', env('UCENTER_URL').'oauth/access_token', [
          'form_params' => [
            "client_id" => env('CLIENT_ID'),
            "client_secret" => env('CLIENT_SECRET'),
            "redirect_uri" => env('CALL_BACK_URL'),
            "code" => $code,
            "grant_type" => "authorization_code",
          ]
        ]);
        $body = $response->getBody();
        $data = json_decode($body,true);
        if(!empty($data) && isset($data['access_token'])){
            $response =$client->request('GET', env('UCENTER_URL').'/api/user/basic?access_token='.$data['access_token']);
            $content = $response->getBody()->getContents();
            var_dump($content);die;
        }






    }
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
