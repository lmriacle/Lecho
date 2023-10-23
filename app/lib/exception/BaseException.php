<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/17
 * Time: 1:24
 */

namespace app\lib\exception;

use think\Exception;

class BaseException extends Exception
{
    // HTTP状态码 400,200
    public $code = 400;
    public $msg = '参数错误';
    // 自定义的错误码
    public $errorCode = 10000;

    /**
     * @param array $params
     */
    //            throw new Exception('参数必须是数组');
    public function __construct($params = [])
    {
        if (!is_array($params)) {
            return;
        }
        if (array_key_exists('code', $params)) {
            $this->code = $params['code'];
        }
        if (array_key_exists('msg', $params)) {
            $this->msg = $params['msg'];
        }
        if (array_key_exists('errorCode', $params)) {
            $this->errorCode = $params['errorCode'];
        }
    }


}