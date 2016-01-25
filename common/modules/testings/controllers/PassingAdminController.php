<?php

namespace common\modules\testings\controllers;

use Yii;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\modules\testings\models\Test;
use common\modules\testings\models\Passing;
use common\modules\testings\models\SearchPassing;
use common\modules\testings\models\Session;
use common\modules\testings\models\SearchQuestionPassing;
use common\modules\testings\models\QuestionPassing;

class PassingAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            'View'   => 'Просмотр прохождения',
            'Create' => 'Создание прохождения',
            'Delete' => 'Удаление прохождения',
            'Manage' => 'Управление прохождениями',
			'Mistake' => 'Ошибка',
			'Change-status' => 'Изменить статус',
			'Change-answer-status' => 'Изменить статус ответа',
			'Statistics' => 'Статистика прохождений',
			'Re-attempt' => 'Переназначение теста'
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
		$searchModel = new SearchQuestionPassing();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, null, null, $id);

		return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'time' => $this->showPeriod($model->time),
        ]);
	}


	public function actionCreate($session)
	{
		$model = new Test;

        Yii::$app->controller->page_title = 'Добавить прохождение';
        Yii::$app->controller->breadcrumbs = [
            ['Список прохождений' => '/testings/passing-admin/manage'],
            'Добавить прохождение'
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['manage', 'session' => $session]);
        } else {
            $form = new \common\components\BaseForm('/common/modules/testings/forms/PassingForm', $model);
            return $this->render('create', [
                'model' => $model,
                'form' => $form->out
            ]);
        }
	}

	public function actionMistake($id)
	{
		$model = $this->findModel($id);

		if ($model->mistake) 
		{
			return $this->redirect(['/testings/mistake-admin/view', 'passing' => $id]);
		} 
		else 
		{
			return $this->redirect(['/testings/mistake-admin/create', 'passing' => $id]);
		}

	}

	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

        return $this->redirect(['manage']);
	}


	public function actionManage($session)
	{
		$searchModel = new SearchPassing();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->controller->page_title = 'Список прохождений';
        Yii::$app->controller->breadcrumbs = [
            'Список прохождений',
        ];

        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'session' => Session::findOne($session),
        ]);
	}

	public function actionStatistics($session)
	{
		$searchModel = new SearchPassing();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->controller->page_title = 'Список прохождений';
        Yii::$app->controller->breadcrumbs = [
            'Список прохождений',
        ];

        return $this->render('statistics', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'session' => Session::findOne($session),
        ]);
	}

	private function showPeriod($time) 
	{
		return sprintf("%02d:%02d:%02d", (int)($time / 3600), (int)(($time % 3600) / 60), $time % 60);
	}

	public function actionReAttempt($id)
	{
		$passing = $this->loadModel($id);

		$passing->attempt = 0;

		$passing->save(false, array('attempt'));

		$subject = 'Тестирование - переназначение теста';
		$body    = Setting::getValue('email_reattempt_body');

		$mailer_letter = MailerLetter::model();
		$body          = $mailer_letter->compileText($body, array(
			'test_name' => $passing->test->name,
		));
		unset($mailer_letter);

      	$result = MailerModule::sendMailUniSender($passing->user->email, $subject, $body);

      	$this->render('reattempt', array(
			'result' => $result
		));
	}

	public function actionChangeAnswerStatus($qp_id) 
	{
		$qp = QuestionPassing::findOne($qp_id);

		if ($qp) 
		{
			if ($qp->user_answer == $qp->answer_text) 
			{
				$qp->user_answer = 'Заведомо неправильный ответ';
			} 
			else 
			{
				$qp->user_answer = $qp->answer_text;
			}

			$qp->save();

			$passing = $this->findModel($qp->passing_id);

			if ($passing) 
			{
				$passing->recountPassResult();
				$passing->save();
			}
		}

		$this->redirect($_SERVER['HTTP_REFERER']);
	}

	public function actionChangeStatus($user, $test) 
	{

        $tp = Passing::find()->where([
    		'user_id' => $user, 
    		'test_id' => $test
    	])->one();

		if ($tp) 
		{
			$tp->delete();
		} 
		else 
		{
			$tp = new Passing;
			$tp->user_id = $user;
			$tp->test_id = $test;
			$tp->save();
		}

		$this->redirect($_SERVER['HTTP_REFERER']);
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
        if (($model = Passing::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
