<?php

class TestingPassingTKIController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            'View'   => 'Просмотр прохождения',
            'Manage' => 'Управление прохождениями',
        );
    }

	private function showPeriod($time) {
		return sprintf("%02d:%02d:%02d", (int)($time / 3600), (int)(($time % 3600) / 60), $time % 60);
	}

	public function actionView($id)
	{

		$model = $this->loadModel($id);

		$questions = new TestingQuestionPassing('search');
		$questions->unsetAttributes();

		$this->render('view', array(
			'model' => $model,
			'questions' => $questions,
			'time' => $this->showPeriod($model->time),
		));
	}

	public function actionManage($session=null)
	{
		$model = new TestingPassing('search');

		$model->unsetAttributes();
		if(isset($_GET['TestingPassing']))
        {
            $model->attributes = $_GET['TestingPassing'];
        }

        $this->render('manage', array(
            'model' => $model,
            'session' => $session ? TestingSession::model()->findByPk($session) : '',
            'sessions' => TestingSession::model()->findAll(),
        ));
	}

	public function loadModel($id)
	{
		$model = TestingPassing::model()->findByPk((int) $id);
		if($model === null)
        {
            $this->pageNotFound();
        }

		return $model;
	}


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'testing-passing-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
