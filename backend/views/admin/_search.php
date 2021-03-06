<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\AdminSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]); ?>

<div class="row">
    <div class="col-md-2">
        <?= $form->field($model, 'username')->textInput()->label('用户名'); ?>
    </div>
    <div class="col-md-1">
        <?= $form->field($model, 'status')->dropDownList(['' => '全部', 0 => '隐藏', 1 => '正常'], ['class' => 'form-control'])->label('状态'); ?>
    </div>
    <div class="col-md-2">
        <div class="form-group" style="margin-top: 24px;">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']); ?>
            <?= Html::resetButton('重置', ['class' => 'btn btn-default']); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

