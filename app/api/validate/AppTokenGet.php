<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2024/4/26
 * Time: 16:47
 */

namespace app\api\validate;

class AppTokenGet extends BaseValidate
{
    protected $rule = [
        'ac' => 'require|isNotEmpty',
        'se' => 'require|isNotEmpty'
    ];
}