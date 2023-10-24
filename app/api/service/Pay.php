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
use think\Config;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;

use app\api\service\Order as OrderService;
use app\api\service\Token as TokenService;

use app\api\model\Order as OrderModel;
use think\exception\DbException;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

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
        return $this->makeWxPreOrder($status['orderPrice']);
    }
    private function makeWxPreOrder($totalPrice)
    {
        //openid
        $openid = TokenService::getCurrentTokenVar('openid');
        if (!$openid){
            throw new TokenException();
        }

        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody('三夏光年');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(\config('secure.pay_back_url'));
        return $this->getPaySignature($wxOrderData);
    }

    private function getPaySignature($wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        // 失败时不会返回result_code
        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] !='SUCCESS'){
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','error');
//            throw new Exception('获取预支付订单失败');
        }
        // prepay_id

        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    private function recordPreOrder($wxOrder){
        // 必须是update，每次用户取消支付后再次对同一订单支付，prepay_id是不同的
        OrderModel::where('id', '=', $this->orderID)
            ->update(['prepay_id' => $wxOrder['prepay_id']]);
    }

    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wxSetting.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());

        $rand = md5(time() . mt_rand(0, 1000));
        $jsApiPayData->SetNonceStr($rand);

        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');

        $sign = $jsApiPayData->MakeSign();
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        unset($rawValues['app_id']);
        return $rawValues;
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