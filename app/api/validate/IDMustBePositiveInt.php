<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/16
 * Time: 17:20
 */

namespace app\api\validate;

class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInt'
//        'num' => 'in:1,2,3'
    ];
    protected $message = [
        'id' => 'id必须是正整数'
    ];
}