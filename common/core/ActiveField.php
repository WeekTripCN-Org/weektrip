<?php

namespace common\core;

use Yii;
use common\helpers\Html;
/**
 * Class ActiveField
 *
 * @package \common\core
 */
class ActiveField extends \yii\widgets\ActiveField
{
    // 配置field模版
    public $template = '<div>{label}{hint}</div>{input}{error}';
    // field 选项
    public $options = ['class' => 'form-group'];
    // input 选项
    public $inputOptions = ['class' => 'form-control c-md-4'];
    // label 标签选项
    public $labelOptions = ['class' => ''];
    // tip提示标签选项
    public $hintOptions = ['tag' => 'span', 'class' => 'help-inline'];
    // error错误选项
    public $errorOptions = ['tag' => 'span', 'class' => 'help-block'];

    public $enableClientValidation = true;

    /**
     * @param array $options
     * @return $this
     * 带前置/后置图标的input
     */
    public function iconTextInput($options = [])
    {
        // 设置图标左右位置
        $icon_pos = isset($options['iconPos']) ? $options['iconPos'] : 'left';
        // 设置图标样式
        $icon_class = isset($options['iconClass']) ? $options['iconClass'] : 'icon-user';
        $icon_tag = Html::tag('i', '', ['class' => $icon_class]);

        $input = Html::activeTextInput($this->model, $this->attribute, $options);
        $input = $icon_pos == 'left' ? $icon_tag . $input : $input . $icon_tag;

        $this->parts['{input}'] = Html::tag('div', $input, ['class' => 'input-icon ' . $icon_pos]);

        return $this;
    }

    /**
     * @param array $items
     * @param array $options
     * @param string $class
     * @return $this
     * radio单选
     */
    public function radioList($items, $options = [], $class = 'mt-radio mt-radio-outline')
    {
        $class = $class ? $class : 'radio';
        $options = array_merge([
            'tag'       => false,
            'encode'    => false,
            'itemOptions'   => [
                'labelOptions' => [
                    'class' => $class,
                    'style' => 'padding-right:20px; margin-bottom:5px;',
                ],
            ],
        ], $options);

        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activeRadioList($this->model, $this->attribute, $items, $options);

        return $this;
    }
}