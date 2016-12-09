<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分类管理';
$this->context->title_sub = '';

\backend\assets\TablesAsset::register($this);

$columns = [
    [
        'class' => \common\core\Checkboxcolumn::className(),
        'name'  => 'id',
        'options'   => ['width' => '20px;'],
        'checkboxOptions' => function ($model, $key, $index, $column) {
            return ['value' => $key, 'label' => '<span></span>', 'labelOptions' => ['class' => 'mt-checkbox mt-check-box-outline', 'style' => 'padding-left:19px;']];
        }
    ],
    [
        'header'    => '名称',
        'attribute' => 'title'
    ],
    [
        'header'    => '标识',
        'options'   => ['width' => '150px;'],
        'attribute' => 'name'
    ],
    [
        'label'     => '状态',
        'options'   => ['width' => '50px;'],
        'content'   => function($model) {
            return '正常';
        }
    ],
    [
        'class'     => 'yii\grid\ActionColumn',
        'header'    => '操作',
        'template'  => '{edit} {addsub} {delete}',
        'options'   => ['width' => '200px;'],
        'buttons'   => [
            'edit'  => function($url, $model, $key) {
                return Html::a('<i class="fa fa-edit"></i>',['edit', 'id' => $model['id']], [
                    'title' => Yii::t('app', '编辑'),
                    'class' => 'btn btn-xs purple'
                ]);
            },
            'addsub'  => function($url, $model, $key) {
                return Html::a('<i class="fa fa-plus"></i>',['add', 'pid' => $model['id']], [
                    'title' => Yii::t('app', '添加子菜单'),
                    'class' => 'btn btn-xs red'
                ]);
            },
            'delete'  => function($url, $model, $key) {
                return Html::a('<i class="fa fa-timess"></i>',['delete', 'id' => $model['id']], [
                    'title' => Yii::t('app', '删除'),
                    'class' => 'btn btn-xs red ajax-get confirm'
                ]);
            },
        ]
    ],
];
?>
    <div class="portlet light portlet-fit portlet-datatable bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject font-dark sbold uppercase">管理信息</span>
            </div>
            <div class="actions">
                <div class="btn-group btn-group-devided">
                    <?=Html::a('添加 <i class="fa fa-plus"></i>',['add','pid'=>Yii::$app->request->get('pid',0)],['class'=>'btn green','style'=>'margin-right:10px;'])?>
                    <?=Html::a('删除 <i class="fa fa-times"></i>',['delete'],['class'=>'btn green ajax-post confirm','target-form'=>'ids','style'=>'margin-right:10px;'])?>
                </div>
                <div class="btn-group">
                    <button class="btn blue btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                        工具箱
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li><a href="javascript:;"><i class="fa fa-pencil"></i> 导出Excel </a></li>
                        <li class="divider"> </li>
                        <li><a href="javascript:;"> 其他 </a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="portlet-body">
            <div>
                <?= $this->render('_search', ['model' => $searchModel]); ?>
            </div>
            <div class="table-container">
                <form class="ids">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider, // 列表数据
                        //'filterModel' => $searchModel, // 搜索模型
                        'options' => ['class' => 'grid-view table-scrollable'],
                        /* 表格配置 */
                        'tableOptions' => ['class' => 'table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer'],
                        /* 重新排版 摘要、表格、分页 */
                        'layout' => '{items}<div class=""><div class="col-md-5 col-sm-5">{summary}</div><div class="col-md-7 col-sm-7"><div class="dataTables_paginate paging_bootstrap_full_number" style="text-align:right;">{pager}</div></div></div>',
                        /* 配置摘要 */
                        'summaryOptions' => ['class' => 'pagination'],
                        /* 配置分页样式 */
                        'pager' => [
                            'options' => ['class'=>'pagination','style'=>'visibility: visible;'],
                            'nextPageLabel' => '下一页',
                            'prevPageLabel' => '上一页',
                            'firstPageLabel' => '第一页',
                            'lastPageLabel' => '最后页'
                        ],
                        /* 定义列表格式 */
                        'columns' => $columns,
                    ]); ?>
                </form>
            </div>
        </div>
    </div>

    <!-- 定义数据块 -->
<?php $this->beginBlock('test'); ?>
    jQuery(document).ready(function() {
    highlight_subnav('category/index'); //子导航高亮
    });
<?php $this->endBlock() ?>
    <!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>