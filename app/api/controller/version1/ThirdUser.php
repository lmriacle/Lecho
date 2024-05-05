<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2024/5/5
 * Time: 23:07
 */

namespace app\api\controller\version1;
use app\api\model\ThirdApp as ThirdAppModel;
use app\lib\exception\BaseException;
use think\exception\DbException;

class ThirdUser
{
    /**
     * @throws DbException
     * @throws BaseException
     */
    public function getAllUserInfo()
    {
        $allUserInfo = ThirdAppModel::all();
        if($allUserInfo->isEmpty()){
            throw new BaseException([
                'errorCode' => 60000,
                'msg' => '用户不存在'
            ]);
        }
        return $allUserInfo;
    }
}