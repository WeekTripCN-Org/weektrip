<?php

namespace backend\assets;
use yii\web\AssetBundle;

/**
 * Class Select2Asset
 *
 * @package \backend\assets
 */
class Select2Asset extends AssetBundle
{
    public $sourcePath = '@backend/metronic';
    public $css = [
        'global/plugins/select2/css/select2.min.css',
        'global/plugins/select2/css/select2-bootstrap.min.css',
    ];
    public $js = [
        'global/plugins/select2/js/select2.full.min.js',
        'pages/scripts/components-select2.min.js',
    ];
    public $depends = [
        'backend\assets\CoreAsset',
    ];
}