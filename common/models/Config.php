<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "config".
 *
 * @property string $id
 * @property string $name
 * @property string $title
 * @property integer $group
 * @property integer $type
 * @property string $value
 * @property string $extra
 * @property string $remark
 * @property string $create_time
 * @property string $update_time
 * @property integer $sort
 * @property integer $status
 */
class Config extends \common\core\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group', 'type', 'create_time', 'update_time', 'sort', 'status'], 'integer'],
            [['value'], 'string'],
            [['name'], 'string', 'max' => 30],
            [['title'], 'string', 'max' => 50],
            [['extra'], 'string', 'max' => 255],
            [['remark'], 'string', 'max' => 100],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '配置标识',
            'title' => '配置说明',
            'group' => '分组',
            'type' => '配置类型',
            'value' => '配置值',
            'extra' => '扩展',
            'remark' => 'Remark',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}
