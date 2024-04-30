<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/17
 * Time: 0:39
 */

namespace app\api\model;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class Banner extends BaseModel
{
    // php think optimize:schema
    // runtime/schema生成数据库缓存字段，提升性能
    protected $hidden = ['delete_time','update_time'];
    public function items()
    {
        /**
         * @param string $model      模型名
         * @param string $foreignKey 关联外键
         * @param string $localKey   关联主键
         */
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

    /**
     * @throws ModelNotFoundException
     * @throws DbException
     * @throws DataNotFoundException
     */
    public static function getBannerByID($id)
    {
        //TODO:根据BannerID 获取Banner信息
        $banner = self::with(['items', 'items.img'])->find($id);
        return $banner;
    }

}