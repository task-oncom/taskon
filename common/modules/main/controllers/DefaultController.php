<?php

namespace common\modules\main\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTest() {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $model = new \common\models\Enctest();
        //$model->data = 'Druppov_A@mail.ru';
        $model->data = 'YQE4W2rOGog=u0TcCx3Lpr1LBkBHEplEuPxJ8+Sq77oQ';
        $model->save();
        $model = \common\models\Enctest::find()->where(['id'=>19])->one();
        print_r($model->attributes);
    }
}
