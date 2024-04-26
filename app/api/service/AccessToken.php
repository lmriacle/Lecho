<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2024/4/26
 * Time: 18:57
 */

namespace app\api\service;

use think\Exception;

class AccessToken
{
    private $tokenUrl;
    const TOKEN_CACHED_KEY = 'access';
    const TOKEN_EXPIRE_IN = 7000;

    function __construct()
    {
        $url = config('wxSetting.access_token_url');
        $url = sprintf($url, config('wxSetting.app_id'), config('wxSetting.app_secret'));
        $this->tokenUrl = $url;
    }

    // 建议用户规模小时每次直接去微信服务器取最新的token
    // 但微信access_token接口获取是有限制的 2000次/天
    /**
     * @throws Exception
     */
    public function get()
    {
        $token = $this->getFromCache();
        if (!$token) {
            return $this->getFromWxServer();
        } else {
            return $token;
        }
    }

    private function getFromCache()
    {
        $token = cache(self::TOKEN_CACHED_KEY);
        if (!$token) {
            return $token;
        }
        return null;
    }

    /**
     * @throws Exception
     */
    private function getFromWxServer()
    {
        $token = curl_get($this->tokenUrl);
        $token = json_decode($token, true);
        if (!$token) {
            throw new Exception('获取AccessToken异常');
        }
        if (!empty($token['errcode'])) {
            throw new Exception($token['errmsg']);
        }
        $this->saveToCache($token);
        return $token['access_token'];
    }

    private function saveToCache($token)
    {
        cache(self::TOKEN_CACHED_KEY, $token, self::TOKEN_EXPIRE_IN);
    }
}