<?php

namespace common\modules\content\controllers;

use Yii;
use common\modules\content\models\CoCategory;
use common\modules\content\models\SearchCoCategory;
use common\modules\content\models\CoContent;
use common\modules\content\models\SearchCoContent;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryAdminController implements the CRUD actions for CoCategory model.
 */
class CategoryAdminController extends AdminController
{
    public static function actionsTitles(){
		return [
			'Manage' 		  => 'Управление модулями',
			'Create' 		  => 'Создание модуля',
			'Update' 		  => 'Редактирование модуля',
			'View'	 		  => 'Просмотр модуля',
            'Delete'          => '',
			'Createcontroller' => 'Создание контроллера',
			'Deletecontroller' => '',
			'Updatecontroller' => '',
		];
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
     * Lists all CoCategory models.
     * @return mixed
     */
    public function actionManage()
    {
        $searchModel = new SearchCoCategory();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		\yii::$app->controller->page_title = 'Управление категориями';
		
		\yii::$app->controller->breadcrumbs = [
					'Управление категориями',
				];


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CoCategory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
		
		$searchModel = new SearchCoContent();
        $dataProvider = $searchModel->search(['category_id' => $id]);
		
		\yii::$app->controller->page_title = 'Просмотр категории';
		
		\yii::$app->controller->breadcrumbs = [
					['Управление категориями' => \yii\helpers\Url::toRoute('manage')],
					$model->name,
				];
		
		return $this->render('view', [
            'model' => $this->findModel($id),
			'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new CoCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
		return $this->actionUpdate();

    }

    /**
     * Updates an existing CoCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id = null)
    {
        if(empty($id)) {
			$model = new CoCategory();
			\yii::$app->controller->page_title = 'Создать категорию';
		
			\yii::$app->controller->breadcrumbs = [
					['Управление категориями' => \yii\helpers\Url::toRoute('manage')],
					'Создать категорию',
				];
			
		}
		else {
			$model = $this->findModel($id);
			\yii::$app->controller->page_title = 'Редактировать категорию';
		
			\yii::$app->controller->breadcrumbs = [
					['Управление категориями' => \yii\helpers\Url::toRoute('manage')],
					$model->name,
				];
			
		}

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(\yii\helpers\Url::toRoute(['manage']));
        } else {
			
			$form = new \common\components\BaseForm('/common/modules/content/forms/CategoryForm', $model);
            return $this->render('update', [
                'model' => $model,
				'form' => $form->out,
            ]);
        }
    }

    /**
     * Deletes an existing CoCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $items = \common\modules\content\models\CoContent::find()->where(['category_id' => $id])->all();
        foreach($items as $item) {
            $item->category_id = null;
            $item->save();
        }

        $this->findModel($id)->delete();

        return $this->redirect(['manage']);
    }

    /**
     * Finds the CoCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CoCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CoCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
