<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/23
 * Time: 0:07
 */

namespace app\api\service;

use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;

use app\api\service\Order as OrderService;
use app\api\service\Token as TokenService;

use app\api\model\Order as OrderModel;
use think\exception\DbException;


class Pay
{
    private $orderID;
    private $orderNO;

    /**
     * @param $orderID
     * @throws Exception
     */
    public function __construct($orderID)
    {
        if(empty($orderID)){
            throw new Exception('订单号不允许为NULL');
        }
        $this->orderID = $orderID;
    }

    /**
     * @throws DbException
     */
    public function pay()
    {
        // 订单号可能根本就不存在
        // $orderNO存在，但是订单号和当前用户不匹配
        // 订单有可能已经被支付过
        // qty检查,
        $this->checkOrderValid();
        $orderService = new OrderService();
        $status = $orderService->checkOrderStock($this->orderID);
        if (!$status['pass']){
            return $status;
        }

    }
    private function makeWxPreOrder()
    {
        //openid
        $openid = TokenService::getCurrentTokenVar('openid');
        if (!$openid){
            throw new TokenException();
        }
    }

    /**
     * @throws DataNotFoundException
     * @throws TokenException
     * @throws OrderException
     * @throws ModelNotFoundException
     * @throws DbException
     * @throws Exception
     */
    private function checkOrderValid()
    {
        $order = OrderModel::where('id', $this->orderID)->find();
        if (!$order){
            throw new OrderException();
        }
        if (!TokenService::isValidOperate($order->user_id)){
            throw new TokenException([
                'msg' => '订单用户不匹配',
                'errorCode' => '10003'
            ]);
        }
        if ($order->status != OrderStatusEnum::UNPAID){
            throw new OrderException([
                'msg' => '订单已支付过啦',
                'errorCode' => '80003',
                'code' => 400
            ]);
        }
        $this->orderNO = $order->order_no;
        return true;
    }

}