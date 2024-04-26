<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/20
 * Time: 9:24
 */

namespace app\api\service;

use app\api\model\ThirdApp;
use app\lib\exception\TokenException;

class AppToken extends Token
{
    /**
     * @throws TokenException
     */
    public function get($ac, $se)
    {
        $app = ThirdApp::check($ac, $se);
        if (!$app) {
            throw new TokenException([
                'msg' => '授权失败',
                'errorCode' => 10004
            ]);
        } else {
            $scope = $app->scope;
            $uid = $app->id;
            $values = [
                'scope' => $scope,
                'uid' => $uid
            ];
            $token = $this->saveToCache($values);
            return $token;
        }
    }

    /**
     * @throws TokenException
     */
    private function saveToCache($values){
        $token = self::generateToken();
        $expire_in = config('setting.token_expire_in');
        $result = cache($token, json_encode($values), $expire_in);
        if(!$result){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $token;
    }
}