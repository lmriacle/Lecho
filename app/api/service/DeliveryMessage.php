<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2024/4/26
 * Time: 18:26
 */

namespace app\api\service;

use app\api\model\User;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use think\Exception;
use think\exception\DbException;

class DeliveryMessage extends WxMessage
{
    const  DELIVERY_MSG_ID = 'JE4LFHOmU4p8j-Lwp9MW7fge2mAfZVea10DeFmDm744';
    //    private $productName;
    //    private $devliveryTime;
    //    private $order

    /**
     * @throws OrderException
     * @throws Exception
     */
    public function sendDeliveryMessage($order, $tplJumpPage = '')
    {
        if (!$order) {
            throw new OrderException();
        }
        $this->tplID = self::DELIVERY_MSG_ID;
        $this->formID = $order->prepay_id;
        $this->page = $tplJumpPage;
        $this->prepareMessageData($order);
        $this->emphasisKeyWord='keyword2.DATA';
        return parent::sendMessage($this->getUserOpenID($order->user_id));
    }

    private function prepareMessageData($order)
    {
        $dt = new \DateTime();
        $data = [
            'keyword1' => [
                'value' => '顺风速运',
            ],
            'keyword2' => [
                'value' => $order->snap_name,
                'color' => '#27408B'
            ],
            'keyword3' => [
                'value' => $order->order_no
            ],
            'keyword4' => [
                'value' => $dt->format("Y-m-d H:i")
            ]
        ];
        $this->data = $data;
    }

    /**
     * @throws DbException
     * @throws UserException
     */
    private function getUserOpenID($uid)
    {
        $user = User::get($uid);
        if (!$user) {
            throw new UserException();
        }
        return $user->openid;
    }
}