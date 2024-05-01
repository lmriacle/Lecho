<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

/**
 * 设置路由规则
 * @access public
 * @param string $rule 路由规则
 * @param string $route 路由地址
 * @param string $type 请求类型
 * @param array $option 路由参数
 * @param array $pattern 变量规则
 * @param string $group 所属分组
 * @return void
 */
//Route::rule('hello/:id', 'sample/Test/hello', 'GET|POST');
// Test API
Route::get('api/:version/second', 'api/:version.Address/second');
Route::get('api/:version/three', 'api/:version.Address/three');

// ProductImg API
Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');
Route::get('api/:version/theme', 'api/:version.Theme/getSimpleList');
Route::get('api/:version/theme/:id', 'api/:version.Theme/getComplexOne');
Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');

/*Route::get('api/:version/product/by_category/:id','api/:version.Product/getAllInCategory');
Route::get('api/:version/product/:id','api/:version.Product/getOneProduct', [], ['id' => '\d+']);
Route::get('api/:version/product/recent','api/:version.Product/getRecent');*/

// Product API
Route::group('api/:version/product', function () {
    Route::get('/by_category/:id', 'api/:version.Product/getAllInCategory');
    Route::get('/:id', 'api/:version.Product/getOneProduct', [], ['id' => '\d+']);
    Route::get('/recent', 'api/:version.Product/getRecent');
});


// Token API
Route::post('api/:version/token/user', 'api/:version.Token/getToken');
Route::post('api/:version/token/verify', 'api/:version.Token/verifyToken');
Route::post('api/:version/token/app', 'api/:version.Token/getAppToken');
Route::post('api/:version/token/wx_info', 'api/:version.Token/updateUserInfo');

// Address API
Route::get('api/:version/address', 'api/:version.Address/getUserAddress');
Route::post('api/:version/address', 'api/:version.Address/createOrUpdateAddress');

// OrderAPI
Route::get('api/:version/order/by_user', 'api/:version.Order/getSummaryByUser');
Route::get('api/:version/order/paginate', 'api/:version.Order/getSummary');
Route::get('api/:version/order/:id', 'api/:version.Order/getDetail', [], ['id' => '\d+']);
Route::post('api/:version/order', 'api/:version.Order/placeOrder');
Route::put('api/:version/order/delivery', 'api/:version.Order/delivery');

// Pay API
Route::post('api/:version/pay/pre_order', 'api/:version.Pay/getPreOrder');
Route::post('api/:version/pay/notify', 'api/:version.Pay/receiveNotify');
Route::post('api/:version/pay/re_notify', 'api/:version.Pay/redirectNotify');

// User API
Route::post('api/:version/user/wx_info', 'api/:version.User/updateUserInfo');



