<?php

namespace backend\assets;

use yii\web\AssetBundle;
/**
 * Class FileinputAsset
 *
 * @package \backend\assets
 */
class FileinputAsset extends AssetBundle
{
    public $sourcePath = '@backend/metronic';
    public $css = [
        'global/plugins/bootstrap-fileinput/bootstrap-fileinput.css',
    ];
    public $js = [];
    //public $jsOptions = [];
    public $depends = [
        'backend\assets\CoreAsset',
    ];
}