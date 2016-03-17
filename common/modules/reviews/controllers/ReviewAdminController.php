<?php

namespace common\modules\reviews\controllers;

use Yii;
use common\modules\reviews\models\Reviews;
use common\modules\reviews\models\SearchReviews;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ReviewAdminController implements the CRUD actions for Reviews model.
 */
class ReviewAdminController extends AdminController
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
            "Create"           	=> "Добавление отзыва",
            "Update"           	=> "Редактирование отзыва",
            "View"             	=> "Просмотр отзыва",
            "Manage"           	=> "Управление отзывами",
            "Delete"           	=> "Удаление отзыва",
            "Update-answer"      => "Ответить",
        ];
    }

    /**
     * Create / Update answer
     * $id integer
     * @return mixed
     */
    public function actionUpdateAnswer($id) 
    {
        $model = $this->findModel($id);

        \yii::$app->controller->page_title = 'Ответ на отзыв <small>'.$model->user->email.'</small>';
        \yii::$app->controller->breadcrumbs = [
            ['Список отзывов' => '/reviews/review-admin/manage'],
            'Ответ на отзыв'
        ];
        //if(empty($_POST['Reviews']['admin_id'])) $model->addError('admin_id', 'Необходимо выбрать оператора');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['manage']);
        } else {
            $form = new \common\components\BaseForm('/common/modules/reviews/forms/AnswerForm', $model);
            return $this->render('updateanswer', [
                'model' => $model,
                'form' => $form->out,
            ]);
        }
    }
    /**
     * Lists all Reviews models.
     * @return mixed
     */
    public function actionManage()
    {
        $searchModel = new SearchReviews();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        \yii::$app->controller->page_title = 'Список отзывов';
        \yii::$app->controller->breadcrumbs = [
            'Список отзывов'
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Reviews model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Reviews model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Reviews();

        \yii::$app->controller->page_title = 'Добавить отзыв';
        \yii::$app->controller->breadcrumbs = [
            ['Список отзывов' => '/reviews/review-admin/manage'],
            'Добавить отзыв'
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            return $this->redirect(['manage']);
        } 
        else 
        {
            //die(print_r($model->errors));
            $form = new \common\components\BaseForm('/common/modules/reviews/forms/ReviewForm', $model);
            return $this->render('create', [
                'model' => $model,
                'form' => $form->out,
            ]);
        }
    }

    /**
     * Updates an existing Reviews model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        \yii::$app->controller->page_title = 'Редактировать отзыв';
        \yii::$app->controller->breadcrumbs = [
            ['Список отзывов' => '/reviews/review-admin/manage'],
            'Редактировать отзыв'
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            return $this->redirect(['manage']);
        } 
        else 
        {
            $form = new \common\components\BaseForm('/common/modules/reviews/forms/ReviewForm', $model);
            return $this->render('update', [
                'model' => $model,
                'form' => $form->out,
            ]);
        }
    }

    /**
     * Deletes an existing Reviews model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['manage']);
    }

    /**
     * Finds the Reviews model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Reviews the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reviews::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
