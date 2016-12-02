<?php

namespace backend\assets;
use yii\web\AssetBundle;

/**
 * Class TablesAsset
 *
 * @package \backend\assets
 */
class TablesAsset extends AssetBundle
{
    public $sourcePath = '@backend/metronic';
    public $css = [
        'global/plugins/datatables/datatables.min.css',
        'global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css',
    ];

    public $js = [
        'global/scripts/datatable.js',
        'global/plugins/datatables/datatables.min.js',
        'global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js',
    ];

    public $depends = [
        'backend\assets\CoreAsset',
    ];
}