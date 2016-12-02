<?php

namespace backend\assets;

use yii\web\AssetBundle;
/**
 * Class LayoutAsset
 *
 * @package \backend\assets
 */
class LayoutAsset extends AssetBundle
{
    public $sourcePath = '@backend/metronic';
    public $css = [
        'global/css/components-rounded.min.css',
        'global/css/plugins.min.css',

        'layouts/layout/css/layout.min.css',
        'layouts/layout/css/themes/darkblue.min.css',
        'layouts/layout/css/custom.min.css',
    ];
    public $js = [
        'layouts/layout/scripts/layout.min.js',
        'layouts/layout/scripts/demo.min.js',
        'layouts/global/scripts/quick-sidebar.min.js',
        'other/js/common.js'
    ];
    public $depends = [
        'backend\assets\CoreAsset',
    ];
}