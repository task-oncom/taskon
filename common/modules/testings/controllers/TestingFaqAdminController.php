<?php

class TestingFaqAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            'Create' => 'Создание справки',
            'Update' => 'Редактирование справки',
            'Delete' => 'Удаление',
            'Manage' => 'Управление справкой',
        );
    }

	public function actionCreate()
	{
		$model = new TestingFaq;
		
		$form = new BaseForm('testings.TestingFaqForm', $model);

		if(isset($_POST['TestingFaq']))
		{
			$model->attributes = $_POST['TestingFaq'];
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

		$form = new BaseForm('testings.TestingFaqForm', $model);

		if(isset($_POST['TestingFaq']))
		{
			$model->attributes = $_POST['TestingFaq'];
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
		$model=new TestingFaq('search');
		$model->unsetAttributes();
		if(isset($_GET['TestingFaq']))
        {
            $model->attributes = $_GET['TestingFaq'];
        }

		$this->render('manage', array(
			'model' => $model,
		));
	}


	public function loadModel($id)
	{
		$model = TestingFaq::model()->findByPk((int) $id);
		if($model === null)
        {
            $this->pageNotFound();
        }

		return $model;
	}


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'testing-faq-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
