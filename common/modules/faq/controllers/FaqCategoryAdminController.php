<?php

namespace common\modules\faq\controllers;

use Yii;
use common\modules\faq\models\FaqCategory;
use common\modules\faq\models\SearchFaqCategory;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FaqCategoryAdminController implements the CRUD actions for FaqCategory model.
 */
class FaqCategoryAdminController extends AdminController
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

    /**
     * Lists all FaqCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchFaqCategory();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FaqCategory model.
     * @param integer $id
     * @param string $name
     * @return mixed
     */
    public function actionView($id, $name)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $name),
        ]);
    }

    /**
     * Creates a new FaqCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FaqCategory();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'name' => $model->name]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FaqCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $name
     * @return mixed
     */
    public function actionUpdate($id, $name)
    {
        $model = $this->findModel($id, $name);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'name' => $model->name]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FaqCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param string $name
     * @return mixed
     */
    public function actionDelete($id, $name)
    {
        $this->findModel($id, $name)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the FaqCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $name
     * @return FaqCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $name)
    {
        if (($model = FaqCategory::findOne(['id' => $id, 'name' => $name])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
