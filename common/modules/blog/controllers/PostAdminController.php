<?php

namespace common\modules\blog\controllers;

use Yii;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\ArrayHelper;

use common\modules\languages\models\Languages;
use common\modules\blog\models\Post;
use common\modules\blog\models\PostLang;
use common\modules\blog\models\PostTag;
use common\modules\blog\models\SearchPost;

/**
 * PostAdminController implements the CRUD actions for Post model.
 */
class PostAdminController extends AdminController
{
    public static function actionsTitles(){
        return [
            'Manage'          => 'Управление записями',
            'Create'          => 'Добавление записи',
            'Update'          => 'Редактирование записи',
            'Delete'          => 'Удаление записи',
            'View'            => 'Просмотр записи',
            'Autocomplete'    => 'Автокомплит для поля тегов'
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionManage()
    {
        $searchModel = new SearchPost();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
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
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();

        if (Yii::$app->request->isPost) 
        {
            $model->attributes = Yii::$app->request->post('Post');

            $transaction = Yii::$app->db->beginTransaction();

            try 
            {
                if($model->save())
                {
                    $transaction->commit();
                }

                return $this->redirect(['manage']);
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
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) 
        {
            $model->attributes = Yii::$app->request->post('Post');

            $transaction = Yii::$app->db->beginTransaction();

            try 
            {
                if($model->save())
                {
                    $transaction->commit();
                }

                return $this->redirect(['manage']);
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
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionAutocomplete($term)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return array_keys(ArrayHelper::map(PostTag::find()->where(['like', 'name', $term])->all(), 'name', 'id'));
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
