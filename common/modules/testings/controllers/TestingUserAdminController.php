<?php

namespace common\modules\testings\controllers;

use Yii;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

use common\modules\testings\models\TestingUser;
use common\modules\testings\models\SearchTestingUser;
use common\modules\testings\models\SearchTestingUserGroup;

class TestingUserAdminController extends AdminController
{
	private $_marked;
	
    public static function actionsTitles()
    {
        return array(
            'View'   => 'Просмотр пользователя',
            'Create' => 'Создание пользователя',
            'Update' => 'Редактирование пользователя',
            'Delete' => 'Удаление пользователя',
            'Manage' => 'Управление пользователями',
            'Manage-group' => 'Управление группами',
            'UpdateMark' => 'Пометка пользователй',
            'ResetMark' => 'Сброс пометок',
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

	public function getMarked($session)
	{
		$session = intval($session);
		if(!isset($this->_marked[$session]))
		{
			$session_key = 'notify_session_'.$session;
			
			if(isset(Yii::$app->session[$session_key])) 
            {
				$data = unserialize(Yii::$app->session[$session_key]);

				if($data === FALSE)
                {
					$data = [];
                }
			} 
            else 
            {
				$data = [];
			}

			$this->_marked[$session] = $data;
		}
		
		return $this->_marked[$session];
	}
	
	public function setMarked($session, $data)
	{		
		$session = intval($session);
		$session_key = 'notify_session_'.$session;
		
		$this->_marked[$session] = $data;
		Yii::$app->session[$session_key] = serialize($data);
	}
	
	public function checkMark($data, $row) 
    {
		return in_array($data->id, $this->getMarked());
	}
	
	public function actionUpdateMark($session)
	{
		if(!isset($_POST['data']) || !is_array($_POST['data']))
        {
			return;
        }

		$toggle = $_POST['data'];
		
		$data = $this->getMarked($session);
		
		$remove = []; $append = [];

		foreach($toggle as $key => $value) 
        {
			if($value) 
            {
				$append[] = $key;
            }
			else
            {
				$remove[] = $key;
            }
		}
		if(!empty($append))
        {
			$data = array_merge($data, $append);
        }
      
        $data = array_unique($data);
			
		if(!empty($remove))
        {
			$data = array_diff($data, $remove);
        }
			
		$this->setMarked($session, $data);
        $qty = count($data);

		echo Json::encode([
            'qty' => $qty,
            'title' => "Разослать выделенным ($qty)",
        ]);
	}
  
    public function actionResetMark($session)
    {
		$this->setMarked($session, []);

		echo Json::encode(array(
            'qty' => 0,
            'title' => "Разослать выделенным",
        ));   
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
