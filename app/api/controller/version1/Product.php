<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/19
 * Time: 10:37
 */

namespace app\api\controller\version1;

use app\api\model\Product as ProductModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ProductException;
use app\lib\exception\SuccessMessage;
use app\api\validate\Count;
use think\Exception;


class Product
{
    /**
     * @throws Exception
     */
    public function getRecent($count = 30)
    {
        (new Count())->goCheck();
        $products = ProductModel::getMostRecent($count);
        if ($products->isEmpty()) {
            throw new ProductException();
        }
        return $products->hidden(['summary']);
//        return 'success';
    }

    /**
     * @throws Exception
     */
    public function getAllInCategory($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $products = ProductModel::getProductsByCategoryID($id);
        if($products->isEmpty()){
            throw new ProductException();
        }
        return $products->hidden(['summary']);
    }

    /**
     * @throws Exception
     */
    public function getOneProduct($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModel::getProductDetail($id);
        if(!$product){
            throw new ProductException();
        }
        return $product;
    }

    /**
     * @throws Exception
     */
    public function addProduct($id = '', $quantity = '')
    {
        $validate = new IDMustBePositiveInt();
        $validate->goCheck();
        $data = input('post.');
        $product = ProductModel::find($data['id']);
        if (!$product) {
            // 商品不存在，返回错误提示
            throw new ProductException();
        }
        // 商品存在，增加商品数量
        $product->stock += $data['quantity'];
        $product->from = 1;
        $product->save();
        return json(new SuccessMessage(), 201);
    }
}