<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/19
 * Time: 13:20
 */

namespace app\lib\exception;

class CategoryException extends BaseException
{
    public $code = 404;
    public $msg = '指定的类目不存在，请检查类目的ID';
    public $errorCode = 50000;
}