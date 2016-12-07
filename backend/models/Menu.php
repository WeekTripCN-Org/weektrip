<?php

namespace backend\models;

use Yii;
/**
 * Class Menu
 *
 * @package \backend\models
 */
class Menu extends \common\models\Menu
{
    public function rules()
    {
        return [
            [['title', 'url'], 'required'],
            [['pid', 'sort', 'hide', 'status'], 'integer'],
            [['title', 'group'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @param $id
     * @return array
     * 递归获取其所有父栏目
     */
    public static function getParentMenus($id)
    {
        $path = [];
        $nav  = static::find()->select(['id', 'pid', 'title'])->where(['id' => $id])->asArray()->one();
        $path[] = $nav;
        if ($nav['pid'] > 0) {
            $path = array_merge(static::getParentMenus($nav['pid']), $path);
        }
        return $path;
    }
}