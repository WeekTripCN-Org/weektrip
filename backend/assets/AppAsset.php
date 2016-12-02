<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{

    public $basePath = '@backend/metronic';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];
    public $depends = [
        'backend\assets\IeAsset',
        'backend\assets\CoreAsset',
        'backend\assets\LayoutAsset',
    ];

    /**
     * @param $view \yii\web\view
     * @param $jsFile
     * 定义按需加载JS方法
     */
    public static function addScript($view, $jsFile)
    {
        $view->registerJsFile($jsFile, [AppAsset::className(), 'depends' => 'backend\assets\AppAsset']);
    }

    /**
     * @param $view \yii\web\View
     * @param $cssFile
     * 定义按需加载css方法
     */
    public static function addCss($view, $cssFile)
    {
        $view->registerCssFile($cssFile, [AppAsset::className(), 'depends' => 'backend\assets\AppAsset']);
    }
}
