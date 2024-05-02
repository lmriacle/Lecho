<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/19
 * Time: 14:40
 */

namespace app\api\model;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class User extends BaseModel
{
    protected $autoWriteTimestamp = true;

    public function address()
    {
        return $this->hasOne('UserAddress', 'user_id', 'id');
    }

    /**
     * @throws ModelNotFoundException
     * @throws DbException
     * @throws DataNotFoundException
     */
    public static function getByOpenID($id)
    {
        $user = self::where('openid', 'eq', $id)->find();
        return $user;
    }

    public static function updateInfo($updateData, $uid)
    {
        self::update($updateData, ['id' => $uid]);
    }


}