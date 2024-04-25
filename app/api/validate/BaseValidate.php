<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/16
 * Time: 17:48
 */

namespace app\api\validate;

use app\lib\exception\ParameterException;
use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    /**
     * 获取http传入的参数
     * 对参数进行校验
     * @throws Exception
     */
    public function goCheck()
    {
        $params = Request::instance()->param();
        $result = $this->batch()->check($params);
        if (!$result) {
            throw new ParameterException([
                'msg' => $this->error,
            ]);
        } else {
            return true;
        }
    }

    protected function isPositiveInt($value, $rule = '', $data = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            return false;
//            return $field . '必须是正整数';
        }
    }

    protected function isNotEmpty($value, $rule = '', $data = '', $field = '')
    {
        if (empty($value)) {
            return false;
        }
        return true;
    }

    /**
     * @throws ParameterException
     */
    public function getDataByRule($arrays)
    {
        if (array_key_exists('user_id', $arrays) | array_key_exists('uid', $arrays)) {
            // 不允许包含user_id或者uid，防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名user_id或者uid'
            ]);
        }
        $newArray = [];
        foreach ($this->rule as $key => $value) {
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }

//    public function isMobile($value)
//    {
//        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
//        $result = preg_match($rule, $value);
//        if($result){
//            return true;
//        }
//        return false;
//    }
    public function isMobile($value)
    {
        $mobileRule = '/^1(3|4|5|7|8)[0-9]\d{8}$/';
        // 座机号码正则表达式
        $phoneRule = '/^0\d{2,3}-\d{7,8}$/';
        // 验证手机号码
        if (preg_match($mobileRule, $value)) {
            return true;
        }
        // 验证座机号码
        if (preg_match($phoneRule, $value)) {
            return true;
        }
        return false;
    }
}