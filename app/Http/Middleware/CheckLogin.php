<?php

namespace App\Http\Middleware;

use App\Exceptions\APIException;
use App\Service\Abstracts\StudentServiceAbstract;
use Closure;
use Illuminate\Support\Facades\App;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $studentService = App::make('App\Service\Abstracts\StudentServiceAbstract');
        if ($token = $request->header('token')) {
            $student = $studentService->getStudentByToken($token);
            if(!$student){
                throw new APIException("无效token",APIException::INVALID_LOGIN);
            }
            if(time() > strtotime($student->token_expires_time)){
                throw new APIException("过期token",APIException::INVALID_LOGIN);
            }

        }else{
            throw new APIException("未登陆",APIException::INVALID_LOGIN);
        }
        return $next($request);
    }
}
