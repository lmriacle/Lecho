<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/19
 * Time: 14:51
 */

return [
    'app_id'     => 'wxe43b7e4647748b0d',
    'app_secret' => 'beecbe4458dfb0056e68028b818cd059',
    'login_url'  => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
    // 微信获取access_token的url地址
    'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?" . "grant_type=client_credential&appid=%s&secret=%s"
];