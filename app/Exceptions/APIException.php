<?php
/**
 * Created by PhpStorm.
 * User: pony
 * Date: 2018/3/15
 * Time: 16:33
 */

namespace App\Exceptions;


use Throwable;

class APIException extends \Exception
{
    const MISS_PARAM = 1001; //缺失参数

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}