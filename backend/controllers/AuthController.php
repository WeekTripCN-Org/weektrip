<?php

namespace backend\controllers;

use Yii;
use backend\models\Admin;
use common\models\AuthAssignment;
/**
 * Class AuthController
 *
 * @package \backend\controllers
 */
class AuthController extends BaseController
{
    public $authManager;

    public function init()
    {
        parent::init();
        $this->authManager = Yii::$app->authManager;
    }

    public function actionIndex()
    {
        $this->setForward();
        $roles = Yii::$app->authManager->getRoles();

        return $this->render('index', [
            'roles' => $roles
        ]);
    }

    /**
     * 添加角色
     * 角色表的“rule_name”字段必须为NULL，否则会出错
     * 详见yii\rbac\BaseManager 203行
     */
    public function actionAdd()
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('param');
            $role = Yii::$app->authManager->createRole($data['name']);
            $role->type = 1;
            $role->description = $data['description'];
            if (Yii::$app->authManager->add($role)) {
                $this->success('更新成功', $this->getForward());
            }
            $this->error('更新失败');
        }
    }

    /**
     * 编辑角色
     */
    public function actionEdit()
    {
        $item_name = Yii::$app->request->get('role');
        $role = Yii::$app->authManager->getRole($item_name);

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('param');
            $role->name = $data['name'];
            $role->description = $data['description'];
            if (Yii::$app->authManager->update($item_name, $role)) {
                $this->success('更新成功', $this->getForward());
            }
            $this->error('更新失败');

            return $this->render('edit', ['role' => $role]);
        }
    }

    /**
     * @param $role 角色名称
     * 删除角色，同时会删除auth_assignment、auth_item_child、auth_item中关于$role的内容
     */
    public function actionDelete($role)
    {
        $role = Yii::$app->authManager->getRole($role);
        if (Yii::$app->authManager->remove($role)) {
            $this->success('删除成功', $this->getForward());
        }
        $this->error('删除失败');
    }

    /**
     * @param $role
     * @return string
     * 角色授权
     */
    public function actionAuth($role)
    {
        if (Yii::$app->request->isPost) {
            $rules = Yii::$app->request->post('rules');
            // 判断角色是否存在
            if (!$parent = Yii::$app->authManager->getRole($role)) {
                $this->error('角色不存在');
            }
            // 删除角色所有child
            Yii::$app->authManager->removeChildren($parent);

            if (is_array($rules)) {
                foreach ($rules as $rule) {
                    // 更新auth_rule表 与 auth_item表
                    Yii::$app->authManager->saveRule($rule);
                    // 更新auth_item_child表
                    Yii::$app->authManager->saveChild($parent->name, $rules);
                }
            }
            $this->success('更新权限成功', $this->getForward());
        }
        // 获取栏目节点
        $node_list = $this->returnNodes();
        $auth_rules = Yii::$app->authManager->getChildren($role);
        $auth_rules = array_keys($auth_rules);

        return $this->render('auth', [
            'node_list' => $node_list,
            'auth_rules'=> $auth_rules,
            'role'  => $role
        ]);
    }

    /**
     * @param $role
     * @return string
     * 授权用户列表
     */
    public function actionUser($role)
    {
        $this->setForward();
        $uids = Yii::$app->authManager->getUserIdsByRole($role);
        $uids = implode(',', array_unique($uids));

        $_where = 'uid in ('.$uids.')';
        return $this->render('user', [
            'dataProvider'  => $this->lists1(new Admin(), $_where),
        ]);
    }
}