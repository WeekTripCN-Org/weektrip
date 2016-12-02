<?php

namespace backend\assets;

use yii\web\AssetBundle;
/**
 * Class CoreAsset
 *
 * @package \backend\assets
 */
class CoreAsset extends AssetBundle
{
    public $sourcePath = '@backend/metronic';
    public $css = [
        'http://fonts.useso.com/css?family=Open+Sans:400,300,600,700&subset=all',
        'global/plugins/font-awesome/css/font-awesome.min.css',
        'global/plugins/simple-line-icons/simple-line-icons.min.css',
        'global/plugins/bootstrap/css/bootstrap.min.css',
        'other/css/style.css',
        'global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
    ];
    public $js = [
        'global/plugins/jquery.min.js',
        'global/plugins/bootstrap/js/bootstrap.min.js',
        'global/plugins/js.cookie.min.js',
        'global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        'global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'global/plugins/jquery.blockui.min.js',
        'global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',

        'global/scripts/app.min.js',
    ];

    public $depends = [];
}