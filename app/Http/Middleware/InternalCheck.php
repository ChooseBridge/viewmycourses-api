<?php

namespace App\Http\Middleware;

use App\Exceptions\APIException;
use Closure;

class InternalCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->get('token');
        $time = $request->get('time');
        $key = env('INTERNAL_KEY');
        $newToken = md5($time . $key);
        if ($token != $newToken) {
            throw new APIException(APIException::ILLGAL_OPERATION, "非法的操作");
        }
        return $next($request);
    }
}
