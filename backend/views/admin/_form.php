<?php

use yii\helpers\Html;
use common\core\ActiveForm;
use common\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'form-aaa'
    ]
]); ?>

<?= $form->field($model, 'username')->iconTextInput([
    'class'     => 'form-control c-md-2',
    'iconPos'   => 'left',
    'iconClass' => 'icon-user',
    'placeholder'   => 'username'
])->label('用户名'); ?>

<?= $form->field($model, 'password')->iconTextInput([
    'class'     => 'form-control c-md-2',
    'iconPos'   => 'left',
    'iconClass' => 'icon-lock',
    'placeholder'   => '修改时密码不变请清空'
])->label('密码'); ?>

<?= $form->field($model, 'email')->iconTextInput([
    'class'     => 'form-control c-md-2',
    'iconPos'   => 'left',
    'iconClass' => 'icon-envelope',
    'placeholder'   => 'Email Address'
])->label('邮箱'); ?>

<?= $form->field($model, 'mobile')->iconTextInput([
    'class'     => 'form-control c-md-2',
    'iconPos'   => 'left',
    'iconClass' => 'fa fa-mobile',
    'placeholder'   => 'Mobile'
])->label('手机号'); ?>

<?= $form->field($model, 'status')->radioList(['1' => '正常', '0' => '隐藏'])->label('用户状态'); ?>

<div class="form-actions">
    <?= Html::submitButton('<i class="icon-ok"></i> 确定', ['class' => 'btn blue ajax-post', 'target-form' => 'form-aaa']) ?>
    <?= Html::button('取消', ['class' => 'btn']) ?>
</div>

<?php ActiveForm::end(); ?>


