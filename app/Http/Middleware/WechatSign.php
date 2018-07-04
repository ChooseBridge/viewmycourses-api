<?php

namespace App\Http\Middleware;

use App\Exceptions\APIException;
use Closure;

class WechatSign
{
    public function calcSign($data) {
        $enc = "";

        foreach ($data as $key => $value) {
            $enc .= "$key:$value;";
        }

        $enc .= "ch00sebr1dge";

        return md5($enc);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data = $request->json()->all();
        $sign = $data["sign"];
        unset($data["sign"]);

        $correct_sign = $this->calcSign($data);

        if (!($correct_sign == $sign)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
