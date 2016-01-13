<?php

namespace common\modules\content\controllers;

use Yii;
use common\modules\content\models\CoBlocks;
use common\modules\content\models\SearchCoBlocks;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BlockAdminController implements the CRUD actions for CoBlocks model.
 */
class BlockAdminController extends AdminController
{
    public static function actionsTitles(){
        return [
            'Manage' 		  => 'Управление блоками',
            'Create' 		  => 'Добавление контента',
            'Update' 		  => 'Редактирование контента',
            'Delete' 		  => 'Удаление контента',
            'View'	 		  => 'Просмотр контента',
            'Createcontent'	  => 'Добавление данных контента',
            'Updatecontent'   => 'Редактирование данных контента',
            'Deletecontent'   => 'Удаление данных контента',
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
        return $this->actionUpdate();
    }

    /**
     * Updates an existing CoBlocks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id=null)
    {
        if(empty($id)){
            $model = new CoBlocks();
            \yii::$app->controller->page_title = 'Создание блока';
            \yii::$app->controller->breadcrumbs = [
                ['Список блоков' => \yii\helpers\Url::toRoute('manage')],
                'Создание блока'
            ];
        }
        else {
            $model = $this->findModel($id);
            \yii::$app->controller->page_title = 'Редактирование блока';
            \yii::$app->controller->breadcrumbs = [
                ['Список блоков' => \yii\helpers\Url::toRoute('manage')],
                'Редактирование блока'
            ];
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['manage']);
        } else {
            $form = new \common\components\BaseForm('/common/modules/content/forms/BlockForm', $model);
            return $this->render('update', [
                'model' => $model,
                'form' => $form->out,
            ]);
        }
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
