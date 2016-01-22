<?php

class TestingGammaAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            'View'   => 'Просмотр гаммы продукции',
            'Create' => 'Создание гаммы продукции',
            'Update' => 'Редактирование гаммы продукции',
            'Delete' => 'Удаление гаммы продукции',
            'Manage' => 'Управление гаммами продукции',
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
		$model = new TestingGamma;
		
		$form = new BaseForm('testings.TestingGammaForm', $model);
		
		// $this->performAjaxValidation($model);

		if(isset($_POST['TestingGamma']))
		{
			$model->attributes = $_POST['TestingGamma'];
			if($model->save())
            {
                $this->redirect(array('manage'));
            }
		}

		$this->render('create', array(
			'form' => $form,
		));
	}


	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		$form = new BaseForm('testings.TestingGammaForm', $model);

		// $this->performAjaxValidation($model);

		if(isset($_POST['TestingGamma']))
		{
			$model->attributes = $_POST['TestingGamma'];
			if($model->save())
            {
                $this->redirect(array('manage'));
            }
		}

		$this->render('update', array(
			'form' => $form,
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


	public function actionManage()
	{
		$model=new TestingGamma('search');
		$model->unsetAttributes();
		if(isset($_GET['TestingGamma']))
        {
            $model->attributes = $_GET['TestingGamma'];
        }

		$this->render('manage', array(
			'model' => $model,
		));
	}


	public function loadModel($id)
	{
		$model = TestingGamma::model()->findByPk((int) $id);
		if($model === null)
        {
            $this->pageNotFound();
        }

		return $model;
	}


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'testing-gamma-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
