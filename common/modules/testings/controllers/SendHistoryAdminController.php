<?php

namespace common\modules\testings\controllers;

use Yii;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\modules\testings\models\SendHistory;
use common\modules\testings\models\SearchSendHistory;

class SendHistoryAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            'Manage' => 'Управление пользователями',
            'Resend-dublicates' => '',
        );
    }

	public function actionManage($session)
	{
		$searchModel = new SearchSendHistory();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->controller->page_title = 'История отправки дубликатов';
        Yii::$app->controller->breadcrumbs = [
            'История отправки дубликатов',
        ];

        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'session_id' => $session
        ]);
	}

	public function actionResendDublicates()
	{
		if(Yii::app()->request->isAjaxRequest)
		{	
			if(Yii::app()->request->getPost('email') && Yii::app()->request->getPost('id'))
			{
				$model = TestingSendHistory::model()->findByPK(Yii::app()->request->getPost('id'));

				$path = Yii::getPathOfAlias('webroot') . TestingSendHistory::FOLDER_PATH;
				$file = $path . $model->file;

				if($model->file && file_exists($file))
				{
					$body    = Setting::getValue('email_multiple_user_test_notice_body');
					$subject = Setting::getValue('email_test_notice_head');

					$mailer_letter = MailerLetter::model();
					$body          = $mailer_letter->compileText($body, array(
						'test_link' => CHtml::link(Yii::app()->controller->createAbsoluteUrl('/testings/testingTest'), Yii::app()->controller->createAbsoluteUrl('/testings/testingTest')),
					));
					unset($mailer_letter);
					$attachments = array(
						$model->file => $file
					);

					MailerModule::sendMailUniSender(Yii::app()->request->getPost('email'), $subject, $body, $model, $attachments);

					echo CJavaScript::jsonEncode(array(
						'success' => true, 
						'message' => 'Отправка прошла успешно'
					));
				}
				else
				{
					echo CJavaScript::jsonEncode(array(
						'success' => false, 
						'message' => 'Файл возможно был удален!'
					));
				}
			}
			else
			{
				echo CJavaScript::jsonEncode(array(
					'success' => false, 
					'message' => 'Данные введены не верно!'
				));
			}
		}
		else
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
			
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
        if (($model = SendHistory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
