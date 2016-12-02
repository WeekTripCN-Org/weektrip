<?php

namespace common\helpers;

use Yii;
use yii\helpers\BaseHtml;

/**
 * Class Html
 *
 * @package \common\helpers
 */
class Html extends BaseHtml
{
    /**
     * @param $url
     * @param string $params
     * @param bool $isUrl
     * @return string
     * 生成图片路径
     */
    public static function src ($url, $params = '', $isUrl = false)
    {
        if ($isUrl === false) {
            return Yii::$app->params['upload']['url'] . $url;
        }
        $query = 'path=' . $url;
        if ($params) {
            $query .= '&'.$params;
        }
        if (Yii::$app->params['storage_encrypt']) {
            $query = 'path='.base64_encode($query);
        }
        return Yii::getAlias('@storageUrl').'/index.php?' . $query;
    }
}