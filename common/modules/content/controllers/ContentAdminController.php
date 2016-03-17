<?php

namespace common\modules\content\controllers;

use Yii;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

use common\modules\content\models\CoContent;
use common\modules\content\models\CoContentLang;
use common\modules\languages\models\Languages;
use common\modules\content\models\SearchCoContent;
use common\modules\content\models\SearchCoContentData;
use common\models\MetaTags;


/**
 * ContentAdminController implements the CRUD actions for CoContent model.
 */
class ContentAdminController extends AdminController
{
    public static function actionsTitles(){
		return [
			'Manage' 		  => 'Управление контентом',
			'Create' 		  => 'Добавление контента',
			'Update' 		  => 'Редактирование контента',
			'Delete' 		  => 'Удаление контента',
			'View'	 		  => 'Просмотр контента',
            'Copy'            => 'Копирование страниц',
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
     * Lists all CoContent models.
     * @return mixed
     */
    public function actionManage()
    {
        $searchModel = new SearchCoContent();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        \yii::$app->controller->page_title = 'Список страниц';
        \yii::$app->controller->breadcrumbs = [
            'Список страниц'
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CoContent model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
			'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CoContent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CoContent;
        
        Yii::$app->controller->page_title = 'Добавить страницу';
    
        Yii::$app->controller->breadcrumbs = [
            ['Управление контентом' => \yii\helpers\Url::toRoute('manage')],
            'Добавить страницу',
        ];

        if (Yii::$app->request->isPost) 
        {
            $transaction = Yii::$app->db->beginTransaction();

            try 
            {
                $model->attributes = Yii::$app->request->post('CoContent');

                if($model->save())
                {
                    $transaction->commit();
                    return $this->redirect(['manage']);
                }
            } 
            catch (\Exception $e) 
            {
                $transaction->rollBack();
                throw $e;
            }
        } 

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CoContent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$model = $this->findModel($id);

		Yii::$app->controller->page_title = 'Редактировать страницу';
	
		Yii::$app->controller->breadcrumbs = [
			['Управление контентом' => \yii\helpers\Url::toRoute('manage')],
			$model->url,
		];

        if (Yii::$app->request->isPost) 
        {
            $transaction = Yii::$app->db->beginTransaction();

            try 
            {
                $model->attributes = Yii::$app->request->post('CoContent');

                if($model->save())
                {
                    $transaction->commit();
                    return $this->redirect(['manage']);
                }
            } 
            catch (\Exception $e) 
            {
                $transaction->rollBack();
                throw $e;
            }
        }

		return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CoContent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['manage']);
    }

    public function actionCopy($id) 
    {        
        $model = $this->findModel($id);

        $model->url = null;

        Yii::$app->controller->page_title = 'Копирование страницы';
    
        Yii::$app->controller->breadcrumbs = [
            ['Управление контентом' => \yii\helpers\Url::toRoute('manage')],
            $model->url,
        ];

        if (Yii::$app->request->isPost) 
        {
            $transaction = Yii::$app->db->beginTransaction();

            try 
            {
                $model = new CoContent;

                $model->attributes = Yii::$app->request->post('CoContent');

                if($model->save())
                {
                    $transaction->commit();
                    return $this->redirect(['manage']);
                }
            } 
            catch (\Exception $e) 
            {
                $transaction->rollBack();
                throw $e;
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the CoContent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CoContent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CoContent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
