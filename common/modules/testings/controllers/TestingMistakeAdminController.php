<?php

class TestingMistakeAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            'View'   => 'Просмотр ошибки',
            'Create' => 'Создание ошибки',
            'Update' => 'Редактирование ошибки',
            'Delete' => 'Удаление ошибки',
            'Manage' => 'Управление ошибками',
        );
    }


	public function actionView($passing)
	{
		$this->render('view', array(
			'model' => TestingMistake::model()->find('passing_id = :passing',array(':passing'=>$passing)),
		));
	}


	public function actionCreate($passing)
	{
		$model = new TestingMistake;

		$form = new BaseForm('testings.TestingMistakeForm', $model);

		// $this->performAjaxValidation($model);

		if(isset($_POST['TestingMistake']))
		{
			$model->attributes = $_POST['TestingMistake'];
			$model->passing_id = $passing;
			if($model->save()) {

				// назначение повторного тестирования
				if (isset($_POST['retest'])) {
					$pass = new TestingPassing;
					$oldpass = TestingPassing::model()->findByPk($model->passing_id);
					$pass->attributes = $oldpass->attributes;
					$pass->is_passed = null;
					$pass->pass_date = null;
					$pass->attempt = 0;
					$pass->pass_date_start = '';
					$pass->save();
				}

                $this->redirect(array('/testings/testingPassingAdmin/manage','session'=>$model->passing->test->session_id));
            }
		}

		$this->render('create', array(
			'form' => $form,
		));
	}

	public function actionUpdate($passing)
	{
		$model = TestingMistake::model()->find('passing_id = :passing',array(':passing'=>$passing));;

		$form = new BaseForm('testings.TestingMistakeForm', $model);

		// $this->performAjaxValidation($model);

		if(isset($_POST['TestingMistake']))
		{
			$model->attributes = $_POST['TestingMistake'];
			if($model->save())
            {

				// назначение повторного тестирования
				if (isset($_POST['retest'])) {
					$pass = new TestingPassing;
					$oldpass = TestingPassing::model()->findByPk($model->passing_id);
					$pass->attributes = $oldpass->attributes;
					$pass->is_passed = null;
					$pass->pass_date = null;
					$pass->attempt = 0;
					$pass->pass_date_start = '';
					$pass->save();
				}

                $this->redirect(array('/testings/testingPassingAdmin/manage','session'=>$model->passing->test->session_id));
            }
		}

		$this->render('update', array(
			'form' => $form,
		));
	}


	public function loadModel($id)
	{
		$model = TestingMistake::model()->findByPk((int) $id);
		if($model === null)
        {
            $this->pageNotFound();
        }

		return $model;
	}


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'testing-mistake-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
