<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\Alert;

//$this->context->admins['username'] = 'Test';

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 8]><html class="ie8 no-js" lang="<?= Yii::$app->language ?>"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="<?= Yii::$app->language ?>"><![endif]-->
<!--[if !IE]><!-->
<html lang="<?= Yii::$app->language ?>">
<!--<![endif]>-->
    <head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?= Html::encode($this->title) ?> | 控制台 WEEKTRIP.cn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <?php $this->head() ?>
    <link rel="shortcut icon" href="<?= Yii::getAlias('@web/favicon.ico') ?>">
    <script>
        var BaseUrl = "<?= Yii::getAlias('@web') ?>";
    </script>
</head>
<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
<?php $this->beginBody() ?>

<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER-->
    <div class="page-header-inner">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?= Yii::getAlias('@web'); ?>">
                <img src="<?= Yii::getAlias('@web/static/images/logo.png') ?>" alt="logo" class="logo-default">
            </a>
            <div class="menu-toggler sidebar-toggler"><span></span></div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN HORIZANTAL MENU 一级栏目-->
        <?php $this->beginContent('@app/views/layouts/public/menu.php') ?>
        <?php $this->endContent() ?>
        <!-- END HORIZANTAL MENU -->

        <!-- BEGIN HEADER SEARCH BOX -->
        <form action="" class="search-form" method="get">
            <div class="input-group">
                <input type="text" class="form-control" name="s" placeholder="搜索">
                <span class="input-group-btn">
                            <a href="javascript:void(0);" class="btn submit">
                                <i class="icon-magnifier"></i>
                            </a>
                        </span>
            </div>
        </form>
        <!-- END HEADER SEARCH BOX -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER 手机版栏目图标 -->
        <a href="javascript:void(0);" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"><span</a>
        <!-- END RESPONSIVE MENU TOGGLER -->

        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!-- BEGIN NOTIFICATION DROPDOWN 通知消息-->
                <?php $this->beginContent('@app/views/layouts/public/notice.php'); ?>
                <?php $this->endContent(); ?>
                <!-- END NOTIFICATION DROPDOWN 通知消息-->

                <!-- BEGIN USER LOGIN DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <img alt="" class="img-circle" src="<?=Yii::getAlias('@web/static/images/avatar2.jpg')?>" />
                        <span class="username username-hide-on-mobile"> <?=$this->context->admins['username']?> </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li><a href="#"><i class="icon-user"></i> 个人主页 </a></li>
                        <li><a href="#"><i class="icon-calendar"></i> 日历 </a></li>
                        <li><a href="#"><i class="icon-envelope-open"></i> 收件箱<span class="badge badge-danger"> 3 </span></a></li>
                        <li><a href="#"><i class="icon-rocket"></i> 我的任务<span class="badge badge-success"> 7 </span></a></li>
                        <li class="divider"> </li>
                        <li><a href="#"><i class="icon-lock"></i> 锁屏 </a></li>
                        <li><a href="<?=Url::toRoute('login/logout')?>"><i class="icon-key"></i> 注销 </a></li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->

                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-quick-sidebar-toggler">
                    <a href="<?=Url::toRoute('login/logout')?>" class="dropdown-toggle">
                        <i class="icon-logout"></i>
                    </a>
                </li>
                <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"></div>
<!-- BEGIN CONTAINER 正文内容 -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- BEGIN SIDEBAR -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <!-- 二级子栏目 -->
            <?php $this->beginContent('@app/views/layouts/public/menu-sub.php') ?>
            <?php $this->endContent() ?>
            <!-- END SIDEBAR MENU -->

            <!--  窄屏幕（手机版）下显示的栏目-->
            <?php $this->beginContent('@app/views/layouts/public/menu-mobile.php') ?>
            <?php $this->endContent() ?>

        </div>
        <!-- END SIDEBAR -->
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">

        <!-- 表单操作ajax弹出提示 -->
        <style>
            .fixed{position: fixed!important;}
            .alert{color: #c09853;font-weight: bold;border: 1px solid #fbeed5;background-color: #fcf8e3;}
            #top-alert {display: block;top: 40px;right: 20px;z-index: 9999;margin-top: 20px;padding-top: 12px;padding-bottom: 12px;overflow: hidden;font-size: 16px;}
            .alert-error {color: white;border-color: #eed3d7;background-color: #FF6666;}
            .alert-success {color: #468847;background-color: #CCFF99;border-color: #eed3d7;}
            @media (max-width: 768px) {.alert_left {left:20px; }}
            @media (min-width: 768px) {.alert_left {left:245px; }}
        </style>
        <div id="top-alert" class="fixed alert alert-error alert_left" style="display: none;">
            <button class="close" style="margin-top:6px;">&times;</button>
            <div class="alert-content">这是Ajax弹出内容</div>
        </div>

        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">
            <!-- BEGIN PAGE HEADER-->
            <!-- BEGIN THEME PANEL 设置界面 -->
            <?php $this->beginContent('@app/views/layouts/public/setting.php') ?>
            <?php $this->endContent() ?>
            <!-- END THEME PANEL -->
            <!-- BEGIN PAGE BAR 快速导航 -->
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="<?=Url::to('index/index')?>">主页</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <?php foreach($this->context->breadcrumbs as $breadcrumbs): ?>
                        <li>
                            <a href="#"><?=$breadcrumbs['title']?></a>
                            <i class="fa fa-circle"></i>
                        </li>
                    <?php endforeach ?>
                    <li><a href="#">内容</a></li>
                </ul>
            </div>
            <!-- END PAGE BAR -->
            <!-- BEGIN PAGE TITLE 正副标题 -->
            <h3 class="page-title">
                <?= Html::encode($this->title) ?>
                <small><?= Html::encode($this->context->title_sub) ?></small>
            </h3>
            <!-- END PAGE TITLE-->
            <!-- BEGIN PAGE CONTENT 正文内容 -->
            <div class="row">
                <div class="col-md-12">
                    <?=$content?>
                </div>
            </div>
            <!-- END PAGE CONTENT-->
            <!-- END PAGE HEADER-->
        </div>
        <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
</div>
<!-- END CONTAINER -->

<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner">
        2016 &copy; www.weektrip.cn.
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<?php \backend\assets\LayoutAsset::register($this); ?>
<?php $this->endBody() ?>
<!-- END PAGE LEVEL PLUGINS -->
</body>
</html>

<?php $this->endPage() ?>
