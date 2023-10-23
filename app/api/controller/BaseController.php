<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/21
 * Time: 21:32
 */

namespace app\api\controller;

use app\api\service\Token as TokenService;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Controller;
use think\Exception;

class BaseController extends Controller
{
    /**
     * @throws Exception
     * @throws TokenException
     * @throws ForbiddenException
     */
    protected function checkPrimaryScope()
    {
        TokenService::needPrimaryScope();
    }

    /**
     * @throws Exception
     * @throws ForbiddenException
     * @throws TokenException
     */
    protected function checkExclusiveScope()
    {
        TokenService::needExclusiveScope();
    }

}