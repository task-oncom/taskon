<?php

namespace common\modules\main\controllers;

use Yii;
use common\modules\main\models\Counts;
use common\modules\main\models\CountsSearch;
use common\components\AdminController;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CountsController implements the CRUD actions for Counts model.
 */
class CountsController extends AdminController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public static function actionsTitles() {
        return [
            'Index' => '',
            'Create' => '',
            'Update' => '',
            'Delete' => '',
            'View' => '',
        ];
    }

    /**
     * Lists all Counts models.
     * @return mixed
     */
    public function actionIndex()
    {
        \yii::$app->controller->page_title = 'Счетчики';
        \yii::$app->controller->breadcrumbs = [
            'Счетчики'
        ];

        $searchModel = new CountsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Counts model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        \yii::$app->controller->page_title = 'Редактировать счетчик ' . $model->name;
        \yii::$app->controller->breadcrumbs = [
            ['Счетчики' => Url::toRoute('/main/counts/index')],
            'Редактировать счетчик ' . $model->name
        ];

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Counts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Counts();

        \yii::$app->controller->page_title = 'Добавить счетчик';
        \yii::$app->controller->breadcrumbs = [
            ['Счетчики' => Url::toRoute('/main/counts/index')],
            'Добавить счетчик'
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Counts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        \yii::$app->controller->page_title = 'Редактировать счетчик ' . $model->name;
        \yii::$app->controller->breadcrumbs = [
            ['Счетчики' => Url::toRoute('/main/counts/index')],
            'Редактировать счетчик ' . $model->name
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Counts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Counts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Counts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Counts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
