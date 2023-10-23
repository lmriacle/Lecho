<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/18
 * Time: 9:46
 */

namespace app\api\model;

class Image extends BaseModel
{
    protected $hidden = ['id', 'delete_time', 'update_time', 'from'];
    public function getUrlAttr($value, $data){
        return $this->prefixImgUrl($value, $data);
    }
}