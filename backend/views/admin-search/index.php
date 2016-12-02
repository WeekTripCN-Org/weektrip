<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Admins';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Admin', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'uid',
            'username',
            'password',
            'salt',
            'email:email',
            // 'mobile',
            // 'reg_time',
            // 'reg_ip',
            // 'last_login_time',
            // 'last_login_ip',
            // 'update_time',
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
