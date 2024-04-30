<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2024/4/27
 * Time: 19:25
 */

namespace app\api\behavior;

class CORS
{
//        // 允许所有域名访问
//        header('Access-Control-Allow-Origin: *');
//        // 允许的请求方法
//        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
//        // 允许的请求头
//        header('Access-Control-Allow-Headers: token, Origin,
//         X-Requested-With, Content-Type, Accept, Authorization');
//        // 是否允许携带身份凭证
//        header('Access-Control-Allow-Credentials: true');
    public function appInit(&$params)
    {
        // 允许所有域名访问
        header('Access-Control-Allow-Origin: *');
        // 允许的请求方法
        header('Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE,OPTIONS');
        // 允许的请求头
        header('Access-Control-Allow-Headers: token,Origin,X-Requested-With,Content-Type,Accept');
        // 是否允许携带身份凭证
        header('Access-Control-Allow-Credentials: true');
        if (request()->isOptions()) {
            exit();
        }
    }
}