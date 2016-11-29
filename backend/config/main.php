<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*'metronic' => [
            'class' => 'dlds\metronic\Metronic',
            'resources'=>'@backend/web/metronic/assets/theme/assets',
            'style'=>\dlds\metronic\Metronic::STYLE_MATERIAL,
            'theme'=>\dlds\metronic\Metronic::THEME_LIGHT,
            'layoutOption'=>\dlds\metronic\Metronic::LAYOUT_FLUID,
            'headerOption'=>\dlds\metronic\Metronic::HEADER_FIXED,
            'sidebarPosition'=>\dlds\metronic\Metronic::SIDEBAR_POSITION_LEFT,
            'sidebarOption'=>\dlds\metronic\Metronic::SIDEBAR_MENU_ACCORDION,
            'footerOption'=>\dlds\metronic\Metronic::FOOTER_FIXED,
        ],*/
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];
