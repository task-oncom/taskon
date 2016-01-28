<?php

namespace common\modules\testings\controllers;

use Yii;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\modules\testings\models\Mistake;
use common\modules\testings\models\Passing;

class MistakeAdminController extends AdminController
{

	public $errorSummaryCssClass = 'error-summary';
	public $encodeErrorSummary = true;


    public static function actionsTitles()
    {
        return array(
            'View'   => 'Просмотр ошибки',
            'Create' => 'Создание ошибки',
            'Update' => 'Редактирование ошибки',
        );
    }


	public function actionView($passing)
	{
		return $this->render('view', [
            'model' => Mistake::find()->where(['passing_id' => $passing])->one(),
        ]);
	}


	public function actionCreate($passing)
	{
		$model = new Mistake;

		Yii::$app->controller->page_title = 'Добавить ошибку';
        Yii::$app->controller->breadcrumbs = [
            ['Список прохождений' => '/testings/passing-admin/manage', 'session' => $model->passing->test->session_id],
            'Добавить ошибку'
        ];

        $model->load(Yii::$app->request->post());
        $model->passing_id = $passing;

        if (Yii::$app->request->isPost && $model->save()) 
        {
        	if ($model->retest) 
        	{
				$pass = new Passing;
				$oldpass = Passing::findOne($model->passing_id);
				$pass->attributes = $oldpass->attributes;
				$pass->is_passed = null;
				$pass->pass_date = null;
				$pass->attempt = 0;
				$pass->pass_date_start = '';
				$pass->save();
			}

        	return $this->redirect(['/testings/passing-admin/manage', 'session' => $model->passing->test->session_id]);
        } 
        else 
        {
            $form = new \common\components\BaseForm('/common/modules/testings/forms/MistakeForm', $model);
            return $this->render('create', [
                'model' => $model,
                'form' => $form->out
            ]);
        }
	}

	public function actionUpdate($passing)
	{
		Yii::$app->controller->page_title = 'Редактировать ошибку';
        Yii::$app->controller->breadcrumbs = [
            ['Список прохождений' => '/testings/passing-admin/manage', 'session' => $model->passing->test->session_id],
            'Редактировать ошибку'
        ];

        $model = Mistake::find()->where(['passing_id' => $passing])->one();

        $model->load(Yii::$app->request->post());
        $model->passing_id = $passing;

        if (Yii::$app->request->isPost && $model->save()) 
        {
        	if ($model->retest) 
        	{
				$pass = new Passing;
				$pass->attributes = $model->passing->attributes;
				$pass->is_passed = null;
				$pass->pass_date = null;
				$pass->attempt = 0;
				$pass->pass_date_start = '';
				$pass->save();
			}

        	return $this->redirect(['/testings/passing-admin/manage', 'session' => $model->passing->test->session_id]);
        } 
        else 
        {
            $form = new \common\components\BaseForm('/common/modules/testings/forms/MistakeForm', $model);
            return $this->render('update', [
                'model' => $model,
                'form' => $form->out
            ]);
        }
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
        if (($model = Answer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
