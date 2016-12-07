<?php

namespace backend\controllers;

use Yii;
use backend\models\Config;
use backend\models\search\ConfigSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;

/**
 * ConfigController implements the CRUD actions for Config model.
 */
class ConfigController extends BaseController
{
    /**
     * Lists all Config models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->setForward();

        $searchModel = new ConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     * 添加配置项
     */
    public function actionAdd()
    {
        $model = $this->findModel(0);
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('Config');
            $data['create_time'] = time();

            if ($this->saveRow($model, $data)) {
                $this->success('操作成功', $this->getForward());
            } else {
                $this->error('操作错误');
            }
        }
        // 模型默认值
        $model->loadDefaultValues();
        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * @return string
     * 编辑配置项
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', 0);
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('Config');
            $data['update_time'] = time();

            if ($this->saveRow($model, $data)) {
                $this->success('操作成功', $this->getForward());
            } else {
                $this->error('操作错误');
            }
        }
        return $this->render('edit', ['model' => $model]);
    }

    /**
     * 删除配置项
     */
    public function actionDelete()
    {
        $model = $this->findModel(0);
        if ($this->delRow($model, 'id')) {
            $this->success('删除成功', $this->getForward());
        } else {
            $this->error('删除失败！');
        }
    }

    /**
     * @return string
     * 以表单的形式展现配置项
     */
    public function actionGroup()
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('param');
            $isSuccess = true;
            foreach ($data as $name => $value) {
                $model = Config::findOne(['name' => $name]);
                $model->value = $value;
                $model->update_time = time();
                if (!$model->save()) {
                    $isSuccess = false;
                    continue;
                }
            }
            if ($isSuccess) {
                $this->success('操作成功', $this->getForward());
            } else {
                $this->error('有配置值修改失败');
            }
        }
        // 添加当前位置到cookie
        $this->setForward();
        $id = Yii::$app->request->get('id', 1);
        // 配置表 分组
        $groups = Config::find()
            ->where(['and', ['group' => $id], ['status' => 1]])
            ->orderBy('sort ASC')
            ->asArray()
            ->all();
        foreach ($groups as $key => $value)
        {
            if ($value['extra']) {
                $groups[$key]['extra'] = Config::parse(3, $value['extra']);
            }
        }
        return $this->render('group', [
            'groups' => $groups
        ]);
    }

    /**
     * Finds the Config model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Config the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if ($id == 0) {
            return new Config();
        }
        if (($model = Config::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
