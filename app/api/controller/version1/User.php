<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2024/5/1
 * Time: 23:38
 */

namespace app\api\controller\version1;

use app\api\controller\BaseController;
use app\api\validate\UserInfoValidate;
use app\api\model\User as UserModel;
use app\api\service\Token as TokenService;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;
use think\Exception;
use think\exception\DbException;

class User extends BaseController
{
    /**
     * 用户授权登陆后，更新用户的信息
     * @throws Exception
     */
    public function updateUserInfo($nickname = '', $extend = '')
    {
        $validate = new UserInfoValidate();
        $validate->goCheck();
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if (!$user) {
            throw new UserException();
        }
//        $userInfo_v = $validate->getDataByRule(input('post.'));
        $user_nickname = $user->nickname;
        $user_extend = $user->extend;

        $nickname = input('post.nickname'); // 假设前端传递的nickname在post参数中
        $extend = input('post.extend'); // 假设前端传递的extend在post参数中

        if (($nickname || $extend)) { // 只有当用户的nickname或extend为空且前端传递了新的nickname或extend时才更新
            $updateData = [];
            if ($nickname) {
                $updateData['nickname'] = $nickname;
            }
            if ($extend) {
                $updateData['extend'] = $extend;
            }
            UserModel::updateInfo($updateData, $uid);
        }
        return json(new SuccessMessage(), 201);
    }

    /**
     * @throws DbException
     * @throws UserException
     */
    public function getUserInfo()
    {
        $allUserInfo = UserModel::all();
        if ($allUserInfo->isEmpty()){
            throw new UserException();
        }
        return $allUserInfo;
    }
}
