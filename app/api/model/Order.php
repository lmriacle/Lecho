<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/22
 * Time: 0:55
 */

namespace app\api\model;

class Order extends BaseModel
{
    protected $hidden = ['user_id', 'delete_time', 'update_time'];
    //自动写入时间
    protected $autoWriteTimestamp = true;

    public static function getSummaryByUser($uid, $page = 1, $size = 15)
    {
        // Paginate::
        $pagingData = self::where('user_id', 'eq', $uid)
            ->order('create_time desc')
            ->paginate($size, true, ['page' => $page]);
        return $pagingData;
    }
}