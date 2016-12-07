<?php

namespace backend\controllers;

use backend\models\Config;
use backend\models\Menu;
use common\helpers\ArrayHelper;
use Yii;
use common\core\Controller;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
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
        Yii::$app->params['web'] = Config::lists();
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
    public function getForward($default = '')
    {
        $default = $default ? $default : Url::toRoute([Yii::$app->controller->id . '/index']);
        if (Yii::$app->getSession()->hasFlash('__forward__')) {
            return Yii::$app->getSession()->getFlash('__forward__');
        } else {
            return $default;
        }
    }

    /**
     * @param string $message
     * @param string $jumpUrl
     * @param bool   $ajax
     * 操作错误跳转的快捷方法
     */
    protected function error($message = '', $jumpUrl = '', $ajax = false)
    {
        $this->dispatchJump($message, 0, $jumpUrl, $ajax);
    }

    /**
     * @param string $message
     * @param string $jumpUrl
     * @param bool   $ajax
     * 操作成功跳转
     */
    protected function success($message = '', $jumpUrl = '', $ajax = false)
    {
        $this->dispatchJump($message, 1, $jumpUrl, $ajax);
    }

    /**
     * @param        $message
     * @param int    $status
     * @param string $jumpUrl
     * @param bool   $ajax
     * 默认跳转操作
     */
    private function dispatchJump($message, $status = 1, $jumpUrl = '', $ajax = false)
    {
        $jumpUrl = !empty($jumpUrl) ? (is_array($jumpUrl) ? Url::toRoute($jumpUrl) : $jumpUrl) : '';
        if (true === $ajax || Yii::$app->request->isAjax) {
            $data = is_array($ajax) ? $ajax : array();
            $data['info'] = $message;
            $data['status'] = $status;
            $data['url'] = $jumpUrl;
            $this->ajaxReturn($data);
        }
        // 操作成功后默认停留3秒
        $waitSecond = 3;
        if ($status) {
            $message = $message ? $message : '提交成功';
            echo $this->renderFile(Yii::$app->params['action_success'], ['message' => $message, 'waitSecond' => $waitSecond, 'jumpUrl' => $jumpUrl,]);
        } else {
            $message = $message ? $message : '发生错误了';
            // 默认返回上页
            $jumpUrl = "javasript:history.back(-1);";
            echo $this->renderFile(Yii::$app->params['action_error'], ['message' => $message, 'waitSecond' => $waitSecond, 'jumpUrl' => $jumpUrl,]);
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

    /**
     * @param $rule 检测的规则
     * @return bool
     *              权限检测
     */
    final protected function checkRule($rule)
    {
        // 超级管理员允许访问任何页面
        if (Yii::$app->params['admin'] == Yii::$app->user->id) {
            return true;
        }
        if (!Yii::$app->user->can($rule)) {
            return false;
        }

        return true;
    }

    /**
     * @param        $model
     * @param array  $where
     * @param string $order
     * @return array
     * 传统分页列表数据集获取方法
     */
    public function lists($model, $where = [], $order = '')
    {
        $query = $model::find()->where($where);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 10,]);
        $data = $query->orderBy($order)->offset($pages->offset)->limit($pages->limit)->all();

        return [$data, $pages];
    }

    /**
     * @param        $model
     * @param array  $where
     * @param string $order
     * @return ActiveDataProvider
     * 列表数据集获取方法
     */
    public function lists1($model, $where = [], $order = '')
    {
        $query = $model::find()->where($where)->orderBy($order)->asArray();
        $dataProvider = new ActiveDataProvider(['query' => $query, 'pagination' => ['pageSize' => 10,]]);

        return $dataProvider;
    }

    /**
     * @param $model
     * @param $data
     * @return bool
     * 修改数据表一条记录的一条值
     */
    public function saveRow($model, $data)
    {
        if (empty($data)) {
            return false;
        }
        if ($model->load($data, '') && $model->validate()) {
            if ($model->save()) {
                return $model;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param        $model
     * @param string $pk
     * @return bool
     * 由表主键删除数据表中的多条记录
     */
    public function delRow($model, $pk = 'id')
    {
        $ids = Yii::$app->request->param($pk, 0);
        $ids = implode(',', array_unique((array)$ids));

        if (empty($ids)) {
            return fasle;
        }

        $_where = $pk . ' in (' . $ids . ')';
        if ($model::deleteAll($_where)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return array $menus
     * 获取控制器菜单数组，二级菜单位于一级菜单的'_child'元素中
     */
    final public function getMenus()
    {
        $menus = [];
        // 获取一级栏目 pid=0 and hide=0
        $menus['main'] = (new \yii\db\Query())->from(Menu::tableName())->where(['pid' => 0, 'hide' => 0])->orderBy('sort ASC')->all();
        $menus['child'] = [];

        // 高亮 当前栏目 及其所有父栏目
        $controller = $this->id;
        $action = $this->action->id;
        $rule = strtolower($controller . '/' . $action);
        $current = (new \yii\db\Query())->select('id')->from(Menu::tableName())->where(['and', 'pid != 0', ['like', 'url', $rule]])->one();
        // 面包屑导航
        $this->breadcrumbs = $nav = Menu::getParentMenus($current['id']);

        foreach ($menus['main'] as $key => $item) {
            if (!is_array($item) || empty($item['title']) || empty($item['url'])) {
                // 弹出错误信息
            }
            // 判断一级栏目权限
            if (!$this->checkRule($item['url'])) {
                unset($menus['main'][ $key ]);
                continue;
            }
            // 获取当前主菜单的子菜单项，其他子菜单不需要获取
            if ($nav[0]['id'] == $item['id']) {
                // 设置当前菜单的一级菜单高亮
                $menus['main'][ $key ]['class'] = 'active';
                // 获取二级菜单
                $second_menu = (new \yii\db\Query())->from(Menu::tableName())->where(['pid' => $item['id'], 'hide' => 0])->orderBy('sort ASC')->all();
                // 判断二级菜单权限
                if ($second_menu && is_array($second_menu)) {
                    foreach ($second_menu AS $skey => $check_menu) {
                        if (!$this->checkRule($check_menu['url'])) {
                            unset($second_menu[ $skey ]);
                            continue;
                        }
                    }
                }
                // 生成child树
                $groups = Menu::find()->select(['group'])->where(['pid' => $item['id'], 'hide' => 0])->groupBy(['group'])->orderBy('sort ASC')->asArray()->column();

                foreach ($groups AS $k => $g) {
                    $menuList = (new \yii\db\Query())->from(Menu::tableName())->where(['pid' => $item['id'], 'hide' => 0, 'group' => $g, 'url' => $second_menu])->orderBy('sort ASC')->all();
                    list($g_name, $g_icon) = strpos($g, '|') ? explode('|', $g) : [$g, 'icon-cogs'];
                    $menus['child'][ $k ]['name'] = $g_name;
                    $menus['child'][ $k ]['icon'] = $g_icon;
                    // 分组内容
                    $menus['child'][ $k ]['_child'] = ArrayHelper::list_to_tree($menuList, 'id', 'pid', 'operater', $item['id']);
                }
            }
        }

        return $menus;
    }

    /**
     * @param bool $tree
     * @return array|mixed
     * 根据menu库，返回权限节点或后台菜单
     */
    public function returnNodes($tree = true)
    {
        // 如果已经生成，直接调用
        static $tree_nodes = array();
        if ($tree && !empty($tree_nodes[ (int)$tree ])) {
            return $tree_nodes[ $tree ];
        }
        if ((int)$tree) {
            $list = (new \yii\db\Query())->select(['id', 'pid', 'title', 'url', 'hide'])->from(Menu::tableName())->orderBy(['sort' => SORT_ASC])->all();
            $nodes = ArrayHelper::list_to_tree($list, $pk = 'id', $pid = 'pid', $child = 'operator', $root = 0);
            foreach ($nodes as $key => $value) {
                if (!empty($value['operator'])) {
                    $nodes[ $key ]['child'] = $value['operator'];
                    unset($nodes[ $key ]['operator']);
                }
            }
        } else {
            $nodes = (new \yii\db\Query())->select(['title', 'url', 'tip', 'pid'])->from(Menu::tableName())->orderBy(['sort' => SORT_ASC])->all();
        }
        // 节点赋值到静态变量中，以供下次调用
        $tree_nodes[ (int)$tree ] = $nodes;

        return $nodes;
    }
}