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
use app\lib\exception\ParameterException;
use app\lib\exception\SuccessMessage;
use think\Exception;
use think\exception\DbException;

class Order extends BaseController
{
    // 做库存量检测
    // 创建顶订单
    // 减库存（预扣除）
    // 如果支付了，则要真的减库存，如果在一定的时间没有去支付这个订单，需要还原库存
    // 防止无限制下单减库存
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder'],
        'checkPrimaryScope' => ['only' => 'getDetail,getSummaryByUser']
    ];

    /**
     * 根据用户id分页获取订单列表（简要信息）
     * @param int $page
     * @param int $size
     * @return array
     * @throws Exception
     */
    public function getSummaryByUser($page = 1, $size = 15)
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
        $data = $pagingOrders->hidden(['snap_items', 'snap_address', 'prepay_id'])->toArray();
        return [
            'data' => $data,
            'current_page' => $pagingOrders->getCurrentPage()
        ];
    }

    /**
     * 获取订单详情
     * @param $id
     * @return OrderModel
     * @throws Exception
     * @throws OrderException
     * @throws DbException
     */
    public function getDetail($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if (!$orderDetail) {
            throw new OrderException();
        }
        return $orderDetail->hidden(['prepay_id']);
    }

    /**
     * 下单
     * @url /order
     * @HTTP POST
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

    /**
     * 获取全部订单简要信息（分页）
     * @param int $page
     * @param int $size
     * @return array
     * @throws ParameterException
     * @throws Exception
     */
    public function getSummary($page = 1, $size = 20)
    {
        (new PagingParameter())->goCheck();
//        $uid = Token::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByPage($page, $size);
        if ($pagingOrders->isEmpty()) {
            return [
                'current_page' => $pagingOrders->currentPage(),
                'data' => []
            ];
        }
        $data = $pagingOrders->hidden(['snap_items', 'snap_address'])
            ->toArray();
        return [
            'current_page' => $pagingOrders->currentPage(),
            'data' => $data
        ];
    }

    /**
     * @throws Exception
     * 发货API
     */
    public function delivery($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $order = new OrderService();
        $success = $order->delivery($id);
        if ($success) {
            return new SuccessMessage();
        }
    }
}