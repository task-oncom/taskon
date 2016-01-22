<?php

class TestingBulkmailAccountAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            'View'   => 'Просмотр аккаунта рассылки',
            'Create' => 'Создание аккаунта рассылки',
            'Update' => 'Редактирование аккаунта рассылки',
            'Delete' => 'Удаление аккаунта рассылки',
            'Manage' => 'Управление аккаунтами рассылки',
        );
    }


	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id),
		));
	}


	public function actionCreate()
	{
		$model = new TestingBulkmailAccount;

		$form = new BaseForm('testings.TestingBulkmailAccountForm', $model);

		// $this->performAjaxValidation($model);

		if(isset($_POST['TestingBulkmailAccount']))
		{
			$model->attributes = $_POST['TestingBulkmailAccount'];
			if($model->save()) {
                $this->redirect(array('/testings/testingBulkmailAccountAdmin/manage'));
            }
		}

		$this->render('create', array(
			'form' => $form,
		));
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);;

		$form = new BaseForm('testings.TestingBulkmailAccountForm', $model);

		// $this->performAjaxValidation($model);

		if(isset($_POST['TestingBulkmailAccount']))
		{
			$model->attributes = $_POST['TestingBulkmailAccount'];
			if($model->save())
            {
                $this->redirect(array('/testings/testingBulkmailAccountAdmin/manage'));
            }
		}

		$this->render('update', array(
			'form' => $form,
		));
	}

	public function actionManage()
	{
		$model=new TestingBulkmailAccount('search');
		$model->unsetAttributes();
		if(isset($_GET['TestingBulkmailAccount']))
        {
            $model->attributes = $_GET['TestingBulkmailAccount'];
        }

		$this->render('manage', array(
			'model' => $model,
		));
	}
	
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$this->loadModel($id)->delete();

			if(!isset($_GET['ajax']))
            {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
		}
		else
        {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
	}
	
	
	public function loadModel($id)
	{
		$model = TestingBulkmailAccount::model()->findByPk((int) $id);
		if($model === null)
        {
            $this->pageNotFound();
        }

		return $model;
	}


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'bulkmail-account-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
