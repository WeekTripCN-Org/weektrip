<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Class LoginAsset
 *
 * @package \backend\assets
 */
class LoginAsset extends AssetBundle
{
    public $sourcePath = '@backend/metronic';
    public $css = [
        'global/css/components-rounded.min.css',
        'global/css/plugins.min.css',
        'pages/css/login.min.css'
    ];
    public $js = [
        'global/plugins/jquery-validation/js/jquery.validate.min.js',
        'global/plugins/jquery-validation/js/additional-methods.min.js',
        'global/plugins/select2/js/select2.full.min.js',
        'pages/scripts/jquery.form.js',
        'pages/scripts/login.js'
    ];
    public $depends = [
        'backend\assets\CoreAsset',
    ];
}