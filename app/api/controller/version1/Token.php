<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/19
 * Time: 14:28
 */

namespace app\api\controller\version1;

use app\api\service\UserToken;
use app\api\validate\TokenGet;
use think\Exception;

class Token
{
    /**
     * @throws Exception
     */
    public function getToken($code = '')
    {
        (new TokenGet())->goCheck();
        $userToken = new UserToken($code);
        $token = $userToken->get();
        return [
            'token' => $token
        ];
    }
}