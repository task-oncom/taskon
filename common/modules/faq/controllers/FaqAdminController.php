<?php

namespace common\modules\faq\controllers;

use Yii;
use common\modules\faq\models\Faq;
use common\modules\faq\models\SearchFaq;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FaqAdminController implements the CRUD actions for Faq model.
 */
class FaqAdminController extends AdminController
{
    public static function actionsTitles(){
        return [
            'Manage' 		  => 'Список вопросов и ответов',
            'Create' 		  => 'Задать вопрос',
            'Update' 		  => 'Дать ответ',
            'View'	 		  => 'Просмотр вопроса/ответа',
            'Delete'	      => 'Просмотр вопроса/ответа',
        ];
    }

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
     * Lists all Faq models.
     * @return mixed
     */
    public function actionManage()
    {
        $searchModel = new SearchFaq();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        \yii::$app->controller->page_title = 'Список вопросов и ответов';
        \yii::$app->controller->breadcrumbs = [
            'Список вопросов и ответов',
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Faq model.
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
     * Creates a new Faq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Faq();

        \yii::$app->controller->page_title = 'Добавить вопрос';
        \yii::$app->controller->breadcrumbs = [
            ['Список вопросов' => '/faq/faq-admin/manage'],
            'Добавить вопрос'
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['manage']);
        } else {
            $form = new \common\components\BaseForm('/common/modules/faq/forms/FaqForm', $model);
            return $this->render('create', [
                'model' => $model,
                'form' => $form->out
            ]);
        }
    }

    /**
     * Updates an existing Faq model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id = null)
    {
        \yii::$app->controller->page_title = 'Ответить';
        \yii::$app->controller->breadcrumbs = [
            ['Список вопросов' => '/faq/faq-admin/manage'],
            'Ответить'
        ];

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['manage']);
        } else {
            $form = new \common\components\BaseForm('/common/modules/faq/forms/FaqForm', $model);
            return $this->render('update', [
                'model' => $model,
                'form' => $form->out
            ]);
        }
    }

    /**
     * Deletes an existing Faq model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['manage']);
    }

    /**
     * Finds the Faq model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Faq the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Faq::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
