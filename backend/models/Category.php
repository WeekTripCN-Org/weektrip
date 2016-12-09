<?php

namespace backend\models;

use Yii;
/**
 * Class Category
 *
 * @package \backend\models
 */
class Category extends \common\models\Category
{
    /**
     * @param $id   菜单ID
     * @return array
     * 递归获取其所有父栏目
     */
    public static function getParents($id)
    {
        $path = [];
        $nav = static::find()->select(['id', 'pid', 'title'])->where(['id' => $id])->asArray()->one();
        $path[] = $nav;
        if ($nav['pid'] > 0) {
            $path = array_merge(static::getParents($nav['pid']), $path);
        }
        return $path;
    }

    /**
     * @param $id
     * @return array|bool|null|\yii\db\ActiveRecord
     * 获取一条数据
     */
    public static function getOne($id)
    {
        if (empty($id)) {
            return false;
        }
        return static::find()->where(['id' => $id])->asArray()->one();
    }
}