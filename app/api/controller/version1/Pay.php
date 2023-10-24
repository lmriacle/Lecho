<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/22
 * Time: 23:45
 */

namespace app\api\controller\version1;

use app\api\controller\BaseController;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePositiveInt;
use app\api\service\Pay as PayService;
use think\Exception;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOrder']
    ];

    /**
     * @throws Exception
     */
    public function getPreOrder($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $pay = new PayService($id);
        return $pay->pay();
    }


    public function receiveNotify()
    {
        // 通知频率，1.检查qty，超卖等 2.更新订单状态 3.减库存
        // 如果成功 返回微信成功处理的信息，否则，需要返回没有成功的处理
        // post：xml格式 不会携带参数
        $notify = new WxNotify();
        $notify->Handle();
    }

    public function notifyConcurrency()
    {
        $notify = new WxNotify();
        $notify->Handle();
    }
    // 这是debug模式测试
    public function redirectNotify()
    {
//        $xmlData = file_get_contents('php://input');
//        Log::error($xmlData);
        $notify = new WxNotify();
        $notify->Handle();
//        $xmlData = file_get_contents('php://input');
//        $result = curl_post_raw('http:/lecho/api/v1/pay/re_notify?XDEBUG_SESSION_START=13133',
//            $xmlData);
//        return $result;
//        Log::error($xmlData);
    }
}