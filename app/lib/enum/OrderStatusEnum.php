<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/23
 * Time: 10:09
 */

namespace app\lib\enum;

class OrderStatusEnum
{
    // 待支付
    const UNPAID          = 1;
    // 已支付
    const PAID            = 2;
    // 已发货
    const DELIVERED       = 3;
    // 已支付，但是qty不足
    const PAID_BUT_OUT_OF = 4;
}