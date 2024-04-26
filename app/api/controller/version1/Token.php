<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/19
 * Time: 14:28
 */

namespace app\api\controller\version1;

use app\api\service\AppToken;
use app\api\service\Token as TokenService;
use app\api\validate\AppTokenGet;
use app\api\service\UserToken;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;
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

    /**
     * @throws ParameterException
     */
    public function verifyToken($token = '')
    {
        if (!$token) {
            throw new ParameterException([
                'token不允许为空'
            ]);
        }
        $valid = TokenService::verifyToken($token);
        return [
            'isValid' => $valid
        ];
    }

    /**
     * 第三方应用获取令牌
     * @url /app_token?
     * @POST ac=:ac se=:secret
     * @throws Exception
     */
    public function getAppToken($ac='', $se='')
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
        (new AppTokenGet())->goCheck();
        $app = new AppToken();
        $token = $app->get($ac, $se);
        return [
            'token' => $token
        ];
    }
}