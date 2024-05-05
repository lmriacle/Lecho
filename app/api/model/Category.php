<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/19
 * Time: 13:06
 */

namespace app\api\model;

class Category extends BaseModel
{
    protected $hidden = [
        'delete_time', 'create_time'
    ];
    public function img(){
        return $this->belongsTo('Image','topic_img_id','id');
    }
}