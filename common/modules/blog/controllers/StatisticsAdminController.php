<?php

namespace common\modules\blog\controllers;

use Yii;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\modules\sessions\models\Session;
use common\modules\blog\models\SearchSession;
use common\modules\blog\models\Post;

/**
 * StatisticsAdminController implements the CRUD actions for Session model.
 */
class StatisticsAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return [
            'Manage'          => 'Управление статистикой',
            'View'            => 'Просмотр тега',
        ];
    }

    /**
     * Lists all Session models.
     * @return mixed
     */
    public function actionManage($id)
    {
        if (($model = Post::findOne($id)) === null) 
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        } 

        $searchModel = new SearchSession();
        $searchModel->blogUrl = '/blog/'.$model->url;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Session model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Session model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Session the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Session::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
