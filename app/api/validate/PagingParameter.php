<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/24
 * Time: 11:25
 */

namespace app\api\validate;

class PagingParameter extends BaseValidate
{
    protected $rule = [
        'page' => 'isPositiveInt',
        'size' => 'isPositiveInt'
    ];

    protected $message = [
        'page' => '分页参数必须是正整数',
        'size' => '分页参数必须是正整数'
    ];
}