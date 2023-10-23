<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/20
 * Time: 1:22
 */

namespace app\api\service;

use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    public static function generateToken()
    {
        $randChars = getRandChar(32);
        // 用三组字符串进行MD5加密
        // 时间戳
        // salt => 盐
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        $salt = config('secure.token.salt');
        return md5($randChars . $timestamp . $salt);
    }

    /**
     * @throws TokenException
     * @throws Exception
     */
    public static function getCurrentTokenVar($key)
    {
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if (!$vars) {
            throw new TokenException();
        } else {
            if (!is_array($vars)) {
                $vars = json_decode($vars, true);
            }
            if (array_key_exists($key, $vars)) {
                return $vars[$key];
            } else {
                throw new Exception('Token不存在，你小子别白费力气了');
            }
        }
    }

    /**
     * @throws Exception
     * @throws TokenException
     */
    public static function getCurrentUid()
    {
        //token得到uid
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    /**
     * @throws Exception
     * @throws ForbiddenException
     * @throws TokenException
     * @User and Admin 都可以访问
     */
    public static function needPrimaryScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope >= ScopeEnum::User) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }

    /**
     * @throws Exception
     * @throws ForbiddenException
     * @throws TokenException
     * @User only access this API
     */
    public static function needExclusiveScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope == ScopeEnum::User) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }

    /**
     * 检测订单号是否与用户的openid匹配
     */
    public static function isValidOperate($checkedID)
    {
        if(!$checkedID){
            throw new Exception('检查UID时必须传入一个被检查的UID');
        }
        $currentOperateUID = self::getCurrentUid();
        if($checkedID == $currentOperateUID){
            return true;
        }
        return false;
    }
}