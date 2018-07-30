<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class WechatController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->json()->all();
        $ucenter_id = $data["ucenter_id"];
        $user = Student::where("ucenter_uid", $ucenter_id);

        if (!$user->count()) {
            return Response::json(array(
                "success" => false,
                "msg" => "用户不存在"
            ));
        }

        $user = $user->first();
        $token = $user->token;

        $user->token_expires_time = date("Y-m-d H:i:s", strtotime("+1 day"));
        $user->save();

        return Response::json(array(
            "success" => true,
            "token" => $token,
        ));
    }
}
