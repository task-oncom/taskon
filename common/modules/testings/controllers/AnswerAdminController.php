<?php

namespace common\modules\testings\controllers;

use Yii;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\modules\testings\models\Answer;
use common\modules\testings\models\SearchAnswer;

class AnswerAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            'View'   => 'Просмотр ответа',
            'Create' => 'Создание ответа',
            'Update' => 'Редактирование ответа',
            'Delete' => 'Удаление ответа',
            'Manage' => 'Управление ответами',
        );
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

	public function actionView($id)
	{
		return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
	}


	public function actionCreate()
	{
		$model = new Answer;

        Yii::$app->controller->page_title = 'Добавить ответ';
        Yii::$app->controller->breadcrumbs = [
            ['Список ответов' => '/testings/question-admin/manage'],
            'Добавить ответ'
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {
        	if(Yii::$app->request->get('question'))
        	{
        		return $this->redirect(['manage', 'question' => Yii::$app->request->get('question')]);
        	}
        	else
        	{
            	return $this->redirect(['manage']);
        	}
        } 
        else 
        {
            $form = new \common\components\BaseForm('/common/modules/testings/forms/AnswerForm', $model);
            return $this->render('create', [
                'model' => $model,
                'form' => $form->out
            ]);
        }
	}


	public function actionUpdate($id)
	{
		Yii::$app->controller->page_title = 'Редактировать ответ';
        Yii::$app->controller->breadcrumbs = [
            ['Список ответов' => '/testings/answer-admin/manage'],
            'Редактировать ответ'
        ];

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            if(Yii::$app->request->get('question'))
        	{
        		return $this->redirect(['manage', 'question' => Yii::$app->request->get('question')]);
        	}
        	else
        	{
            	return $this->redirect(['manage']);
        	}
        } 
        else 
        {
            $form = new \common\components\BaseForm('/common/modules/testings/forms/AnswerForm', $model);
            return $this->render('update', [
                'model' => $model,
                'form' => $form->out
            ]);
        }
	}


	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

        return $this->redirect(['manage']);
	}


	public function actionManage()
	{
		$searchModel = new SearchAnswer();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->controller->page_title = 'Список ответов';
        Yii::$app->controller->breadcrumbs = [
            'Список ответов',
        ];

        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
        if (($model = Answer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
