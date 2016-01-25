<?php

namespace common\modules\testings\controllers;

use Yii;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\modules\testings\components\MarkBoxBehavior;

use common\modules\testings\models\TestingUser;
use common\modules\testings\models\SearchTestingUser;
use common\modules\testings\models\SearchTestingUserGroup;

class TestingUserAdminController extends AdminController
{	
    public static function actionsTitles()
    {
        return array(
            'View'   => 'Просмотр пользователя',
            'Create' => 'Создание пользователя',
            'Update' => 'Редактирование пользователя',
            'Delete' => 'Удаление пользователя',
            'Manage' => 'Управление пользователями',
            'Manage-group' => 'Управление группами',
            'Update-mark' => 'Пометка пользователй',
            'Reset-mark' => 'Сброс пометок',
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
            'marked' => [
                'class' => MarkBoxBehavior::className(),
                'session_key' => 'user-admin',
            ]
        ];
    }

    public function actions()
    {
        return [
            'update-mark' => [
                'class' => \common\modules\testings\components\MarkBoxAction::className(),
            ]
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
		$model = new TestingUser;

        Yii::$app->controller->page_title = 'Добавить пользователя';
        Yii::$app->controller->breadcrumbs = [
            ['Список пользователей' => '/testings/testing-user-admin/manage'],
            'Добавить пользователя'
        ];

        if ($model->load(Yii::$app->request->post())) 
        {
        	$model->manager_id = Yii::app()->user->id;

        	if($model->validate())
        	{
	        	$model->login = $model->generateLogin();
				$model->password = PasswordGenerator::generate(6);
				$model->save();

	            return $this->redirect(['manage']);
	        }
        } 
        
        $form = new \common\components\BaseForm('/common/modules/testings/forms/TestingUserForm', $model);
        return $this->render('create', [
            'model' => $model,
            'form' => $form->out
        ]);
	}


	public function actionUpdate($id)
	{
		Yii::$app->controller->page_title = 'Редактировать пользователя';
        Yii::$app->controller->breadcrumbs = [
            ['Список пользователей' => '/testings/testing-user-admin/manage'],
            'Редактировать пользователя'
        ];

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) 
        {
        	$model->manager_id = Yii::app()->user->id;

        	if($model->validate())
        	{
        		$model->save();
            	return $this->redirect(['manage']);
        	}
        }
            
        $form = new \common\components\BaseForm('/common/modules/testings/forms/TestingAnswerForm', $model);
        return $this->render('update', [
            'model' => $model,
            'form' => $form->out
        ]);        
	}


	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

        return $this->redirect(['manage']);
	}


	public function actionManage()
	{
		$searchModel = new SearchTestingUser();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->controller->page_title = 'Список назначений тестов пользователям';
        Yii::$app->controller->breadcrumbs = [
            'Список назначений тестов пользователям',
        ];

        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}

	public function actionManageGroup()
	{
		$searchModel = new SearchTestingUserGroup();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->controller->page_title = 'Список групп';
        Yii::$app->controller->breadcrumbs = [
            'Список групп',
        ];

        return $this->render('manage-group', [
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
        if (($model = TestingUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
