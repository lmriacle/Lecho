<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/20
 * Time: 13:40
 */

namespace app\api\controller\version1;


use app\api\controller\BaseController;
use app\api\model\User as UserModel;
use app\api\service\Token as TokenService;
use app\api\validate\AddressNew;
use app\lib\exception\SuccessMessage;
use app\lib\exception\TokenException;
use app\lib\exception\UserException;
use app\api\model\UserAddress;
use Exception;


class Address extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => [
            'only' => 'createOrUpdateAddress'
        ]
    ];

    /**
     * @throws \think\Exception
     * @throws TokenException
     * Refactor the code ONE and TWO
     */

    /*    protected function checkPrimaryScope()
        {
            $scope = TokenService::getCurrentTokenVar('scope');
            if ($scope) {
                if ($scope >= ScopeEnum::User) {
                    return true;
                } else {
                    throw new ForbiddenException();
                }
            }else{
                throw new TokenException();
            }
    //    two
    //    protected function checkPrimaryScope()
    //    {
    //        TokenService::needPrimaryScope();
    //    }
        }*/

    /**
     * @throws Exception
     */
    public function createOrUpdateAddress()
    {
        $validate = new AddressNew();
        $validate->goCheck();
        // (new AddressNew())->goCheck();
        // 根据token获取uid
        // 根据uid产找用户数据，判断用户是否存在，如果不存在抛出异常
        // 获取用户从客户端来的地址信息
        // 根据用户地址信息是否存在，判断是否添加/更新
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if (!$user) {
            throw new UserException();
        }
        $dataAddress = $validate->getDataByRule(input('post.'));
        $userAddress = $user->address;
        if (!$userAddress) {
            $user->address()->save($dataAddress);
        } else {
            $user->address->save($dataAddress);
        }
        return json(new SuccessMessage(), 201);
    }

    /**
     * 获取用户地址信息
     * @return UserAddress
     * @throws UserException
     */
    public function getUserAddress()
    {
        $uid = TokenService::getCurrentUid();
        $userAddress = UserAddress::where('user_id', $uid)->find();
        if(!$userAddress){
            throw new UserException([
                'msg' => '用户地址不存在',
                'errorCode' => 60001
            ]);
        }
        return $userAddress;
    }
}