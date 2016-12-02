<?php

namespace common\core;

use Yii;
/**
 * Class ActiveForm
 *
 * @package \common\core
 */
class ActiveForm extends \yii\widgets\ActiveForm
{
    // form 表情的默认属性
    public $options = [
        'class' => '',
    ];
    // 字段默认使用的类
    public $fieldClass = 'common\core\ActiveField';
    public $errorCssClass = 'has-error';
    public $successCssClass = 'has-success';

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @param array $options
     * @return \yii\widgets\ActiveField
     * 使IDE能自动识别$form->field($model, 'name)的返回值
     */
    public function field($model, $attribute, $options = [])
    {
        return parent::field($model, $attribute, $options);
    }
}