<?php

namespace backend\controllers;

use Yii;
use common\core\Controller;
use yii\helpers\Url;

/**
 * Class BaseController
 *
 * @package \backend\controllers
 */
class BaseController extends Controller
{
    public $defaultAction = 'index';
    public $layout = 'main';
    public $menu = [];          // 当前用户允许访问的栏目
    public $breadcrumbs = [];   // 面包屑导航
    public $admins = [];        // 当前登录的管理员信息

    public $title_sub = '';     // 页面子标题或提示

    public function init()
    {
        if (Yii::$app->user->isGuest) {
            $this->redirect(Url::toRoute('/login/login'));
            Yii::$app->end();
        }
        // 获取当前登录用户的信息
        $this->admins = Yii::$app->user->identity->getAttributes();

        // 解析数据库配置，解析后放在Yii::$app->params['web']中
//        Yii::$app->params['web'] = Conf
    }

    /**
     * 标记当前位置到cookie供后续跳转调用
     */
    public function setForward()
    {
        Yii::$app->getSession()->setFlash('__forward__', $_SERVER['REQUEST_URI']);
    }

    /**
     * @param string $default
     * @return mixed|string
     * 获取之前标记的cookie位置
     */
    public function getForward($default='')
    {
        $default = $default ? $default : Url::toRoute([Yii::$app->controller->id. '/index']);
        if (Yii::$app->getSession()->hasFlash('__forward__')) {
            return Yii::$app->getSession()->getFlash('__forward__');
        } else {
            return $default;
        }
    }

    /**
     * @param string $message
     * @param string $jumpUrl
     * @param bool $ajax
     * 操作错误跳转的快捷方法
     */
    protected function error($message = '', $jumpUrl = '', $ajax = false)
    {
        $this->dispatchJump($message, 0, $jumpUrl, $ajax);
    }

    /**
     * @param string $message
     * @param string $jumpUrl
     * @param bool $ajax
     * 操作成功跳转
     */
    protected function success($message = '', $jumpUrl = '', $ajax = false)
    {
        $this->dispatchJump($message, 1, $jumpUrl, $ajax);
    }

    /**
     * @param $message
     * @param int $status
     * @param string $jumpUrl
     * @param bool $ajax
     * 默认跳转操作
     */
    private function dispatchJump($message, $status = 1, $jumpUrl = '', $ajax = false)
    {
        $jumpUrl = !empty($jumpUrl) ? (is_array($jumpUrl) ? Url::toRoute($jumpUrl) : $jumpUrl) : '';
        if (true === $ajax || Yii::$app->request->isAjax) {
            $data           = is_array($ajax) ? $ajax : array();
            $data['info']   = $message;
            $data['status'] = $status;
            $data['url']    = $jumpUrl;
            $this->ajaxReturn($data);
        }
        // 操作成功后默认停留3秒
        $waitSecond = 3;
        if ($status) {
            $message = $message ? $message : '提交成功';
            echo $this->renderFile(Yii::$app->params['action_success'], [
                'message'   => $message,
                'waitSecond'=> $waitSecond,
                'jumpUrl'   => $jumpUrl,
            ]);
        } else {
            $message = $message ? $message : '发生错误了';
            // 默认返回上页
            $jumpUrl = "javasript:history.back(-1);";
            echo $this->renderFile(Yii::$app->params['action_error'], [
                'message'       => $message,
                'waitSecond'    => $waitSecond,
                'jumpUrl'       => $jumpUrl,
            ]);
        }
        exit();
    }

    /**
     * @param $data
     * Ajax方式返回数据到客户端
     */
    protected function ajaxReturn($data)
    {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}