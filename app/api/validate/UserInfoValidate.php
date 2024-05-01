<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2024/5/2
 * Time: 0:00
 */

namespace app\api\validate;

class UserInfoValidate extends BaseValidate
{
    protected $rule = [
        'nickname' => 'require|isNotEmpty',
        'extend'   => 'require|isNotEmpty'
    ];
}