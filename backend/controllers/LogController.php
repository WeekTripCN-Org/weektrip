<?php

namespace backend\controllers;

use Yii;
use common\models\Log;
use backend\models\search\LogSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;

/**
 * LogController implements the CRUD actions for Log model.
 */
class LogController extends BaseController
{
    /**
     * Lists all Log models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->setForward();

        $searchModel = new LogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     * 查看行为日志
     */
    public function actionView()
    {
        $id = Yii::$app->request->get('id', 0);
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionDelete()
    {
        $model = $this->findModel(0);
        if ($this->delRow($model, 'id')) {
            $this->success('删除成功', $this->getForward());
        } else {
            $this->error('删除失败！');
        }
    }

    public function actionClear()
    {
        $res = Log::deleteAll();
        if ($res !== false) {
            $this->success('日志清空成功！');
        } else {
            $this->error('日志清空失败！');
        }
    }
    /**
     * Finds the Log model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Log the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if ($id == 0) {
            return new Log();
        }
        if (($model = Log::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
