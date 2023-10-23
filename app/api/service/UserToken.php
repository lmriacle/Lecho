<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/19
 * Time: 14:41
 */

namespace app\api\service;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use app\lib\exception\WeChatException;
use app\lib\exception\TokenException;
use app\api\model\User as UserModel;
use think\exception\DbException;
use app\lib\enum\ScopeEnum;
use think\Exception;

class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    /**
     * @param $code
     */
    public function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wxSetting.app_id');
        $this->wxAppSecret = config('wxSetting.app_secret');
        $this->wxLoginUrl = sprintf(config('wxSetting.login_url'),
            $this->wxAppID, $this->wxAppSecret, $this->code);
    }

    /**
     * @throws Exception
     */
    public function get()
    {
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result, true);
        if (empty($wxResult)) {
            throw new Exception('获取session_key及openID时异常，微信内部错误');
        } else {
            $loginFail = array_key_exists('errcode', $wxResult);
            if ($loginFail) {
                $this->processLoginError($wxResult);
            } else {
                return $this->grantToken($wxResult);
            }
        }
    }

    /**
     * @throws ModelNotFoundException
     * @throws DbException
     * @throws DataNotFoundException
     * @throws TokenException
     */
    private function grantToken($wxResult)
    {
        //拿到openid
        //数据库看一下openid是否存在
        //存在则不处理，不存在则新增一条user数据
        //生成token,准备缓存数据,写入缓存 key:token value:wxResult,uid,scope是权限
        //返回token
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if ($user) {
            $uid = $user->id;
        } else {
            $uid = $this->newUser($openid);
        }
        $cacheValue = $this->prepareCacheValue($wxResult, $uid);
        return $this->saveToCache($cacheValue);
    }

    /**
     * @throws TokenException
     */
    private function saveToCache($cacheValue)
    {
        $key = self::generateToken();
        $value = json_encode($cacheValue);
        $expire_in = config('setting.token_expire_in');

        $result = cache($key, $value, $expire_in);
        if (!$result) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key;
    }

    private function prepareCacheValue($wxResult, $uid)
    {
        $cacheValue = $wxResult;
        $cacheValue['uid'] = $uid;
        //16代表App用户的权限数值
        $cacheValue['scope'] = ScopeEnum::User;
//        32代表CMS(管理员)的数值
//        $cacheValue['scope'] = 32;
        return $cacheValue;
    }

    // 插入新用户
    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid' => $openid
        ]);
        return $user->id;
    }

    /**
     * @throws WeChatException
     */
    private function processLoginError($wxResult)
    {
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }
}