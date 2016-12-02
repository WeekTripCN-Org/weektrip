<?php

namespace backend\controllers;

/**
 * Class IndexController
 *
 * @package \backend\controllers
 */
class IndexController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}