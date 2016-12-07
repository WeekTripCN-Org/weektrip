<?php

namespace backend\controllers;

use Yii;
use backend\models\Menu;
use backend\models\search\MenuSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends BaseController
{
    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->setForward();
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionAdd()
    {
        $pid = Yii::$app->request->get('pid', 0);
        $model = $this->findModel(0);
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('Menu');
            $data['status'] = 1;

            if ($this->saveRow($model, $data)) {
                    $this->success('操作成功', $this->getForward());
            } else {
                $this->error('操作错误');
            }
        }
        // 设置默认值
        $model->loadDefaultValues();
        $model->pid = $pid;
        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * @return string
     * 编辑
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', 0);
        $model = $this->findModel($id);
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('Menu');
            if ($this->saveRow($model, $data)) {
                $this->success('操作成功', $this->getForward());
            } else {
                $this->error('操作错误');
            }
        }
        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * 删除或批量删除
     */
    public function actionDelete($id)
    {
        $model = $this->findModel(0);
        if ($this->delRow($model, 'id')) {
            $this->success('删除成功', $this->getForward());
        } else {
            $this->error('删除失败!');
        }
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if ($id == 0) {
           return new Menu();
        }
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
