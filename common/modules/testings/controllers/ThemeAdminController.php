<?php

namespace common\modules\testings\controllers;

use Yii;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\modules\testings\models\Theme;
use common\modules\testings\models\SearchTheme;

class ThemeAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            'View'   => 'Просмотр темы',
            'Create' => 'Создание темы',
            'Update' => 'Редактирование темы',
            'Delete' => 'Удаление темы',
            'Manage' => 'Управление темами',
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
		return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
	}

	public function actionCreate()
	{
		$model = new Theme;

        Yii::$app->controller->page_title = 'Добавить тему';
        Yii::$app->controller->breadcrumbs = [
            ['Список тем' => '/testings/theme-admin/manage'],
            'Добавить тему'
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['manage']);
        } else {
            $form = new \common\components\BaseForm('/common/modules/testings/forms/ThemeForm', $model);
            return $this->render('create', [
                'model' => $model,
                'form' => $form->out
            ]);
        }
	}

	public function actionUpdate($id)
	{
		Yii::$app->controller->page_title = 'Редактировать тему';
        Yii::$app->controller->breadcrumbs = [
            ['Список тем' => '/testings/theme-admin/manage'],
            'Редактировать тему'
        ];

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['manage']);
        } else {
            $form = new \common\components\BaseForm('/common/modules/testings/forms/ThemeForm', $model);
            return $this->render('update', [
                'model' => $model,
                'form' => $form->out
            ]);
        }
	}

	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

        return $this->redirect(['manage']);
	}

	public function actionManage()
	{
		$searchModel = new SearchTheme();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->controller->page_title = 'Список тем';
        Yii::$app->controller->breadcrumbs = [
            'Список тем',
        ];

        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
        if (($model = Theme::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
