<?php

namespace common\modules\content\controllers;

use common\models\MetaTags;
use Yii;
use common\modules\content\models\CoContent;
use common\modules\content\models\CoContentData;
use common\modules\content\models\SearchCoContent;
use common\modules\content\models\SearchCoContentData;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
			'Createcontent'	  => 'Добавление данных контента',
			'Updatecontent'   => 'Редактирование данных контента',
			'Deletecontent'   => 'Удаление данных контента',
            'Copypage'        => '',
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

    public function actionCopypage($id) {
        $model = $this->findModel($id);
        $meta = $model->metaTags->attributes;

        \Yii::$app->request->setBodyParams(['MetaTags' => $meta]);
        $newPage = new CoContent();
        $data = $model->attributes;
        unset($data['id']);
        $data['url'] = '';
        $newPage->setAttributes($data);
        $newPage->name .=  ' (Копия)';
        $newPage->save(false);

        $this->redirect(['manage']);
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
        $searchModel = new SearchCoContentData();
        $dataProvider = $searchModel->search(['content_id'=>$id]);

        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
        return $this->actionUpdate();
    }
	
	public function actionCreatecontent($content_id)
    {
        return $this->actionUpdatecontent($content_id);
    }

    /**
     * Updates an existing CoContent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id = null)
    {
        if(empty($id)) {
			$model = new CoContent();

            $meta = new \common\models\MetaTags;
            $meta->language = 'ru';
			
			\yii::$app->controller->page_title = 'Добавить страницу';
		
			\yii::$app->controller->breadcrumbs = [
					['Управление контентом' => \yii\helpers\Url::toRoute('manage')],
					'Добавить страницу',
				];
		}
		else {
			$model = $this->findModel($id);
			$meta = $model->metaTags;
			\yii::$app->controller->page_title = 'Редактировать страницу';
		
			\yii::$app->controller->breadcrumbs = [
					['Управление контентом' => \yii\helpers\Url::toRoute('manage')],
					$model->name,
				];
		}
        //die(print_r($model->metaTag));
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['manage']);
        } else {
            $form = new \common\components\BaseForm('/common/modules/content/forms/ContentForm', $model);
			return $this->render('update', [
                'model' => $model,
                'meta' => $meta,
				'form' => $form->out,
            ]);
        }
    }
	
	public function actionUpdatecontent($content_id, $id = null)
    {
        if(empty($id)) {
			$model = new CoContentData();
			$model->content_id = $content_id;
			\yii::$app->controller->page_title = 'Добавить данные контента';
		
			\yii::$app->controller->breadcrumbs = [
					['Управление данными контента' => \yii\helpers\Url::toRoute(['view', 'id'=>$content_id])],
					'Добавить данные контента',
				];
		}
		else {
			$model = $this->findModelData($id);

			\yii::$app->controller->page_title = 'Редактировать данные контента <small>' . $model->title . '</small>';
		
			\yii::$app->controller->breadcrumbs = [
					['Управление данными контента' => \yii\helpers\Url::toRoute('manage')],
					'Редактировать данные контента ' . $model->title,
				];
		}

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->content_id]);
        } else {
            $form = new \common\components\BaseForm('/common/modules/content/forms/ContentDataForm', $model);
			return $this->render('updatecontent', [
                'model' => $model,
				'form' => $form->out,
            ]);
        }
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
	
	protected function findModelData($id)
    {
        if (($model = CoContentData::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
