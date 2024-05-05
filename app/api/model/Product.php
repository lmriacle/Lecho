<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/18
 * Time: 14:10
 */

namespace app\api\model;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class Product extends BaseModel
{
    protected $hidden = [
        'delete_time', 'main_img_id', 'pivot', 'category_id',
        'update_time'
    ];

    public function imgs()
    {
        return $this->hasMany('ProductImage', 'product_id', 'id');
    }

    public function properties()
    {
        return $this->hasMany('ProductProperty', 'product_id', 'id');
    }

    public function getMainImgUrlAttr($value, $data)
    {
        return $this->prefixImgUrl($value, $data);
    }

    /**
     * @throws ModelNotFoundException
     * @throws DbException
     * @throws DataNotFoundException
     */
    public static function getMostRecent($count)
    {
        $products = self::limit($count)
            ->order('create_time desc')
            ->select();
        return $products;
    }

    /**
     * @throws ModelNotFoundException
     * @throws DbException
     * @throws DataNotFoundException
     */
    public static function getProductsByCategoryID($categoryID)
    {
        $products = self::where('category_id', 'eq', $categoryID)->select();
        return $products;
    }

    /**
     * @throws ModelNotFoundException
     * @throws DbException
     * @throws DataNotFoundException
     */
    public static function getProductDetail($id)
    {
//        $product = self::with('imgs.imgUrl,properties')->find($id);
//        $product = self::with(['imgs.imgUrl','properties'])->find($id);
        $product = self::with([
            'imgs' => function ($query) {
                $query->with(['imgUrl'])
                    ->order('order', 'asc');
            }
        ])
            ->with(['properties'])
            ->find($id);
        return $product;
    }
}