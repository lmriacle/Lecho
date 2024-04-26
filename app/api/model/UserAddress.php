<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/20
 * Time: 15:29
 */

namespace app\api\model;

class UserAddress extends BaseModel
{
    protected $hidden = ['id', 'delete_time', 'user_id'];
    protected $autoWriteTimestamp = true;
}