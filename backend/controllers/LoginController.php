<?php

namespace backend\controllers;

use Yii;
use backend\models\LoginForm;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * Class LoginController
 *
 * @package \backend\controllers
 */
class LoginController extends Controller
{
    public $layout = false;
    public $enableCsrfValidation = false;
    public $defaultAction = 'login';

    /**
     * @param \yii\base\Action $action
     * @return array
     * 行为控制
     */
    public function beforeAction($action)
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'logout'],
                        'allow'   => true,
                    ]
                ],
            ],
        ];
    }

    /**
     * @return array
     * 独立操作
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post(), 'info') && $model->login()) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return $this->render('login', ['model' => $model]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(Url::toRoute('/login/login'));
    }
}