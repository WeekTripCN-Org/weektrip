<?php
use yii\helpers\Html;
use common\core\ActiveForm;
use common\helpers\ArrayHelper;

$this->title = '添加用户';
$this->context->title_sub = '添加后台用户';

?>
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption font-red-sunglo">
            <i class="icon-settings font-red-sunglo"></i>
            <span class="caption-subject bold uppercase">内容信息</span>
        </div>
        <div class="actions">
            <div class="btn-group">
                <a href="javascript:void(0);" class="btn btn-sm green dropdown-toggle" data-toggle="dropdown">
                    工具箱 <i class="fa fa-angle-down"></i>
                </a>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="javascript:void(0);"><i class="fa fa-pencil"></i>导出Excel</a></li>
                    <li class="divider"></li>
                    <li><a href="javascript:;">其他</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="portlet-body form">
        <?= $this->render('_form', [
            'model' => $model,
        ])?>
    </div>
</div>

<?php $this->beginBlock('test'); ?>
jQuery(document).ready(function() {
    hightlight_subnav('admin/index');
});
<?php $this->endBlock();?>

<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>


