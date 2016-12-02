<?php

namespace common\core;

/**
 * Class Checkboxcolumn
 *
 * @package \common\core
 */
class Checkboxcolumn extends \yii\grid\CheckboxColumn
{
    protected function renderFooterCellContent()
    {
        return '<label class="mt-checkbox mt-checkbox-outline" style="padding-left: 19px;">'.parent::renderHeaderCellContent().'<span></span></label>';
    }
}