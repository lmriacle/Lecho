<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/18
 * Time: 0:14
 */

namespace app\api\model;

class BannerItem extends BaseModel
{
    protected $hidden = ['delete_time', 'update_time', 'id', 'img_id', 'banner_id'];

    //关联Image
    public function img()
    {
        /**
         * @param string $foreignKey 关联外键
         * @param string $localKey 关联主键
         * 可以省略
         */
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}
