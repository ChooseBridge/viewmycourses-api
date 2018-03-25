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
    const INVALID_LOGIN = 1002; //无效的登陆
    const ERROR_PARAM = 1003; //错误的参数
    const ILLGAL_OPERATION = 1004; //非法操作
    const OPERATION_EXCEPTION = 1005; //操作异常
    const DATA_EXCEPTION = 1006; //数据异常
    const IS_NOT_VIP = 1007; //不是会员 需要会员权限

    public function __construct( $message = "",  $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}