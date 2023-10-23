<?php
/**
 * Created by : Miracle Liu
 * Author: 小刘励志秃头
 * Date: 2023/10/19
 * Time: 13:05
 */

namespace app\api\controller\version1;

use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;
use think\exception\DbException;

class Category
{
    /**
     * @throws DbException
     * @throws CategoryException
     */
    public function getAllCategories(){
        $categories = CategoryModel::all([],'img');
        if ($categories->isEmpty()){
            throw new CategoryException();
        }
        return $categories;
    }
}