<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/19
 * Time: 11:00
 */

namespace app\lib\exception;

class ProductException extends BaseException
{
    public $code = 404;
    public $msg = '指定的Product不存在，请检查参数';
    public $errorCode = 20000;
}