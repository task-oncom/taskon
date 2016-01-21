<?php

class TestingSendHistoryAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            'Manage' => 'Управление пользователями',
            'ResendDublicates' => '',
        );
    }

	public function actionManage()
	{
		if(!Yii::app()->request->getQuery('session'))
		{
			$this->pageNotFound();
		}

		$model=new TestingSendHistory('search');
		$model->unsetAttributes();
		if(isset($_GET['TestingSendHistory']))
        {
            $model->attributes = $_GET['TestingSendHistory'];
        }

		$this->render('manage', array(
			'model' => $model,
			'session_id' => Yii::app()->request->getQuery('session')
		));
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

	public function loadModel($id)
	{
		$model = TestingSendHistory::model()->findByPk((int) $id);
		if($model === null)
        {
            $this->pageNotFound();
        }

		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'testing-sendhistory-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
