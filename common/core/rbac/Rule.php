<?php

namespace common\core\rbac;

/**
 * Class Rule
 *
 * @package \common\core\rbac
 */
class Rule extends \yii\rbac\Rule
{
    public function execute($user, $item, $params)
    {
        return true;
        // TODO: Implement execute() method.
    }
}