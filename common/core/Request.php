<?php

namespace common\core;

use Yii;
/**
 * Class Request
 *
 * @package \common\core
 */
class Request extends \yii\web\Request
{
    /**
     * @param      $name            参数名
     * @param null $defaultValue    默认值
     * @return array|mixed|null
     * 获取页面GET/POST数据
     */
    public function param($name, $defaultValue = null)
    {
        $value = $this->get($name);
        $value = (!empty($value)) ? $this->get($name) : $this->post($name);
        $value = (!empty($value)) ? $value : $defaultValue;
        return $value;
    }

    /**
     * @param      $name
     * @param null $defaultValue
     * @return int
     * 获取页面GET/POST的int数据
     */
    public function paramInt($name, $defaultValue = null)
    {
        return intval($this->param($name, $defaultValue));
    }
}