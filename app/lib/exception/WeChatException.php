<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/19
 * Time: 15:38
 */

namespace app\lib\exception;

class WeChatException extends BaseException
{
    public $code = 400;
    public $msg = 'The WeChat server interface call failed!';
    public $errorCode = 504;
}