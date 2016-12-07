<?php

namespace backend\models;

use Yii;

/**
 * Class Config
 *
 * @package \backend\models
 */
class Config extends \common\models\Config
{
    /**
     * @return array
     * 获取数据库中的 配置列表
     */
    public static function lists()
    {
        $config = [];
        $data = (new \yii\db\Query())
            ->select(['type', 'name', 'value'])
            ->from(self::tableName())
            ->where(['status' => 1])
            ->all();
        if (!empty($data) && is_array($data)) {
            foreach ($data as $key => $value) {
                $config[$value['name']] = self::parse($value['type'], $value['value']);
            }
        }
        return $config;
    }

    /**
     * @param $type
     * @param $value
     * @return array
     * 根据配置类型解析配置
     */
    public static function parse($type, $value)
    {
        switch ($type) {
            case 3:
                $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
                if (strpos($value, ':')) {
                    $value= [];
                    foreach ($array as $val) {
                        list($k, $v) = explode(':', $val);
                        $value[$k]   = $v;
                    }
                } else {
                    $value = $array;
                }
                break;
        }
        return $value;
    }
}