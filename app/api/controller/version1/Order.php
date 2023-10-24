<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/20
 * Time: 17:17
 */

namespace app\api\controller\version1;

use app\api\controller\BaseController;
use app\api\service\Order as OrderService;
use app\api\service\Token as TokenService;
use app\api\model\Order as OrderModel;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\OrderPlace;
use app\api\validate\PagingParameter;
use app\lib\exception\OrderException;
use think\Exception;

class Order extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder'],
        'checkPrimaryScope'   => ['only' => 'getDetail,getSummaryByUser']
    ];

    public function getSummaryByUser($page = 1 , $size = 15)
    {
        (new PagingParameter())->goCheck();
        $uid = TokenService::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByUser($uid, $page, $size);
        if ($pagingOrders->isEmpty()) {
            return [
                'data' => [],
                'current_page' => $pagingOrders->getCurrentPage()
            ];
        }
        $data = $pagingOrders->hidden(['snap_items','snap_address','prepay_id'])->toArray();
        return [
            'data' => $data,
            'current_page' => $pagingOrders->getCurrentPage()
        ];
    }

    public function getDetail($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if(!$orderDetail){
            throw new OrderException();
        }
        return $orderDetail->hidden(['prepay_id']);
    }

    /**
     * @throws Exception
     */
    public function placeOrder()
    {
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid, $products);
        return $status;
    }
}