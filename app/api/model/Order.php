<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/22
 * Time: 0:55
 */

namespace app\api\model;

use think\exception\DbException;

class Order extends BaseModel
{
    protected $hidden = ['user_id', 'delete_time', 'update_time'];
    //自动写入时间
    protected $autoWriteTimestamp = true;

    public function getSnapItemsAttr($value)
    {
        if (empty($value)) {
            return null;
        }
        return json_decode($value);
    }

    // 读取器
    public function getSnapAddressAttr($value)
    {
        if (empty($value)) {
            return null;
        }
        return json_decode($value);
    }

    /**
     * @throws DbException
     */
    public static function getSummaryByUser($uid, $page = 1, $size = 15)
    {
        // Paginate::
        $pagingData = self::where('user_id', 'eq', $uid)
            ->order('create_time desc')
            ->paginate($size, true, ['page' => $page]);
        return $pagingData;
    }

    /**
     * @throws DbException
     */
    public static function getSummaryByPage($page, $size)
    {
        $pagingData = self::order('create_time desc')
            ->paginate($size, true, ['page' => $page]);
        return $pagingData;
    }
}