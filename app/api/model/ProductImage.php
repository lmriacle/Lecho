<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/20
 * Time: 11:26
 */

namespace app\api\model;

class ProductImage extends BaseModel
{
    protected $hidden = [
        'img_id', 'delete_time', 'product_id'
    ];
    public function imgUrl(){
        return $this->belongsTo('Image', 'img_id');
    }
}