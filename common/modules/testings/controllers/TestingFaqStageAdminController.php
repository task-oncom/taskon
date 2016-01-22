<?php

class TestingFaqStageAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            'Create' => 'Создание пункта справки',
            'Update' => 'Редактирование пункта справки',
            'Delete' => 'Удаление пункта',
            'Manage' => 'Управление пунктами справки',
        );
    }

	public function actionCreate($faq)
	{
		if(!$faq)
		{
			$this->pageNotFound();
		}
		
		$model = new TestingFaqStage;
		
		$form = new BaseForm('testings.TestingFaqStageForm', $model);

		if(isset($_POST['TestingFaqStage']))
		{
			$model->attributes = $_POST['TestingFaqStage'];
			$model->faq_id = $faq;
			if($model->save())
            {
                $this->redirect(array('manage', 'faq' => $faq));
            }
		}

		$this->render('create', array(
			'form' => $form,
		));
	}


	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		$form = new BaseForm('testings.TestingFaqStageForm', $model);

		if(isset($_POST['TestingFaqStage']))
		{
			$model->attributes = $_POST['TestingFaqStage'];
			if($model->save())
            {
                $this->redirect(array('manage', 'faq' => $model->faq_id));
            }
		}

		$this->render('update', array(
			'form' => $form,
			'model' => $model
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
		if(!Yii::app()->request->getQuery('faq'))
		{
			$this->pageNotFound();
		}
		
		$model = new TestingFaqStage('search');
		$model->unsetAttributes();
		if(isset($_GET['TestingFaqStage']))
        {
            $model->attributes = $_GET['TestingFaqStage'];
        }

		$this->render('manage', array(
			'model' => $model,
		));
	}


	public function loadModel($id)
	{
		$model = TestingFaqStage::model()->findByPk((int) $id);
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
