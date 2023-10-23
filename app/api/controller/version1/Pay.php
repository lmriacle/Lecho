<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/22
 * Time: 23:45
 */

namespace app\api\controller\version1;

use app\api\controller\BaseController;
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
}