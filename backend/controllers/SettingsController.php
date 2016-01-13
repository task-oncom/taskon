<?php

namespace backend\controllers;

use Yii;
use common\models\Settings;
use common\models\SearchSettings;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SearchController implements the CRUD actions for Settings model.
 */
class SettingsController extends AdminController
{
    public $page_title = 'SettingsController';

	
	public static function actionsTitles()
    {
        return array(
            "View"            => Yii::t('app', "Просмотр свойства"),
            "Create"          => Yii::t('app', "Добавление свойства"),
            "Update"          => Yii::t('app', "Редактирование свойства"),
            "Delete"          => Yii::t('app', "Удаление свойства"),
            "Manage"		  => Yii::t('app', "Список свойств"),
            "Sort"            => Yii::t('app', "Сортировка"),
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

    /**
     * Lists all Settings models.
     * @return mixed
     */
    public function actionManage($module_id)
    {
        $searchModel = new SearchSettings();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//die(print_r(\Yii::$app->getModule($module_id)));		
		\yii::$app->controller->page_title = 'Настройки модуля';
		
		\yii::$app->controller->breadcrumbs = [
				'Настройки модуля ' .\Yii::$app->getModule($module_id)->name(),
			];

        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'module_id' => $module_id
        ]);
    }

    /**
     * Displays a single Settings model.
     * @param string $id
     * @return mixed
     */
    public function actionView($module_id, $id)
    {
        \yii::$app->controller->page_title = 'Просмотр свойства для модуля <small>' .\Yii::$app->getModule($module_id)->name(). '</small>';
		
		\yii::$app->controller->breadcrumbs = [
			['Свойства модуля ' .\Yii::$app->getModule($module_id)->name() => \yii\helpers\Url::toRoute(['manage', 'module_id'=>$module_id])],
			'Просмотр свойства для модуля ' .\Yii::$app->getModule($module_id)->name(),
		];
		
		return $this->render('view', [
            'model' => $this->findModel($id),
			'module_id' => $module_id
        ]);
    }

    /**
     * Creates a new Settings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($module_id)
    {
        
		return $this->actionUpdate($module_id);
		die('---');
		$model = new Settings();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Settings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($module_id, $id = null)
    {
        
//die(print_r(\common\components\AppManager::getSettingsParam('scoring_low')));		
		if(!empty($id)) {
			$model = $this->findModel($id);
			\yii::$app->controller->page_title = 'Редактирование свойства для модуля <small>' .\Yii::$app->getModule($module_id)->name(). '</small>';
		
			\yii::$app->controller->breadcrumbs = [
				['Свойства модуля ' .\Yii::$app->getModule($module_id)->name() => \yii\helpers\Url::toRoute(['manage', 'module_id'=>$module_id])],
				'Редактирование свойства для модуля ' .\Yii::$app->getModule($module_id)->name(),
			];
		}
		else {
			$model = new Settings();
			$model->module_id = $module_id;
			\yii::$app->controller->page_title = 'Добавление свойства для модуля <small>' .\Yii::$app->getModule($module_id)->name(). '</small>';
		
		\yii::$app->controller->breadcrumbs = [
				['Свойства модуля ' .\Yii::$app->getModule($module_id)->name() => \yii\helpers\Url::toRoute(['manage', 'module_id'=>$module_id])],
				'Добавление свойства для модуля ' .\Yii::$app->getModule($module_id)->name(),
			];
		}

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['manage', 'module_id' => $module_id]);
        } else {
			if($model->hasErrors()) 
				 \Yii::$app->getSession()->setFlash('error', $model->getErrors());

			if($id == 87)
                $form = new \common\components\BaseForm('/backend/forms/SettingsForm1', $model);
            else
                $form = new \common\components\BaseForm('/backend/forms/SettingsForm', $model);

            return $this->render('update', [
                //'model' => $model,
				'form' => $form->out,
				//'module_id' => $module_id
            ]);
        }
    }

    /**
     * Deletes an existing Settings model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($module_id, $id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(\yii\helpers\Url::toRoute(['manage', 'module_id'=>$module_id]));
    }

    /**
     * Finds the Settings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Settings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Settings::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
