<?php

namespace common\modules\content\controllers;

use Yii;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\modules\content\models\CoBlocks;
use common\modules\content\models\CoBlocksLang;
use common\modules\content\models\SearchCoBlocks;
use common\modules\languages\models\Languages;

/**
 * BlockAdminController implements the CRUD actions for CoBlocks model.
 */
class BlockAdminController extends AdminController
{
    public static function actionsTitles(){
        return [
            'Manage' 		  => 'Управление блоками',
            'Create' 		  => 'Добавление блока',
            'Update'          => 'Редактирование блока',
            'Copy' 		      => 'Копирование блока',
            'Delete' 		  => 'Удаление блока',
            'View'	 		  => 'Просмотр блока',
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
     * Lists all CoBlocks models.
     * @return mixed
     */
    public function actionManage()
    {
        $searchModel = new SearchCoBlocks();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        \yii::$app->controller->page_title = 'Список блоков';
        \yii::$app->controller->breadcrumbs = [
            'Список блоков'
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CoBlocks model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CoBlocks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CoBlocks;      
        
        Yii::$app->controller->page_title = 'Добавить блок';
    
        Yii::$app->controller->breadcrumbs = [
            ['Управление блоками' => \yii\helpers\Url::toRoute('manage')],
            'Добавить блок',
        ];

        if (Yii::$app->request->isPost) 
        {
            $transaction = Yii::$app->db->beginTransaction();

            try 
            {
                $model->attributes = Yii::$app->request->post('CoBlocks');

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
     * Updates an existing CoBlocks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);   
        
        Yii::$app->controller->page_title = 'Редактировать блок';
    
        Yii::$app->controller->breadcrumbs = [
            ['Управление блоками' => \yii\helpers\Url::toRoute('manage')],
            'Редактировать блок',
        ];

        if (Yii::$app->request->isPost) 
        {
            $transaction = Yii::$app->db->beginTransaction();

            try 
            {
                $model->attributes = Yii::$app->request->post('CoBlocks');

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

    public function actionCopy($id)
    {
        $model = $this->findModel($id);  

        $model->name = $model->title = null; 
        
        Yii::$app->controller->page_title = 'Копировать блок';
    
        Yii::$app->controller->breadcrumbs = [
            ['Управление блоками' => \yii\helpers\Url::toRoute('manage')],
            'Копировать блок',
        ];

        if (Yii::$app->request->isPost) 
        {
            $transaction = Yii::$app->db->beginTransaction();

            try 
            {
                $model = new CoBlocks;

                $model->attributes = Yii::$app->request->post('CoBlocks');

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
     * Deletes an existing CoBlocks model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['manage']);
    }

    /**
     * Finds the CoBlocks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return CoBlocks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CoBlocks::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
