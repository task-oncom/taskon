<?php

namespace common\modules\blog\controllers;

use Yii;
use common\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

use common\models\Settings;
use common\modules\blog\models\Post;
use common\modules\blog\models\PostTag;
use common\modules\blog\models\PostTagAssign;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends BaseController
{
    public static function actionsTitles()
    {
        return [
            'Index'           => 'Блог',
            'Load'            => 'Подгрузка записей',
            'Tag'             => 'Просмотр тега',
            'View'            => 'Просмотр записи',
            'Send-article'     => 'Послать статью',
        ];
    }

    /**
     * Displays all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->meta_title = Yii::t('menu', 'Blog');

        $query = Post::find()->where(['active' => 1])->limit(Post::PAGE_SIZE)->orderBy('created_at DESC');

        return $this->render('index', [
            'models' => $query->all(),
            'count' => $query->count(),
        ]);
    }

    /**
     * Displays a single Post model.
     * @param string $url
     * @return mixed
     */
    public function actionTag($tag)
    {
        $model = PostTag::find()->where(['name' => $tag])->one();

        if(!$model || !$model->posts)
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $this->meta_title = Yii::t('app', 'Tag') . ': ' . $model->name;

        return $this->render('tag', [
            'model' => $model,
            'count' => $model->getAllPosts()->count()
        ]);
    }

    /**
     * Displays a single Post model.
     * @param string $url
     * @return mixed
     */
    public function actionView($url)
    {
        $model = $this->findModelByUrl($url);
        
        $this->meta_title = $model->metaTag->title;
        $this->meta_description = $model->metaTag->description;
        $this->meta_keywords = $model->metaTag->keywords;

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * @return mixed
     */
    public function actionLoad()
    {
        if(Yii::$app->request->isAjax)
        {
            $offset = Yii::$app->request->post('offset');
            $tag = Yii::$app->request->post('tag');

            Yii::$app->response->format = Response::FORMAT_JSON;

            $query = Post::find()->where(['active' => 1])->orderBy(Post::tableName().'.created_at DESC');

            if($tag)
            {
                $query = $query->joinWith('postTagAssigns')->andWhere([PostTagAssign::tableName().'.tag_id' => $tag]);
            }

            $models = $query->limit(Post::PAGE_SIZE)->offset($offset)->all();
            $count = $query->count();

            return [
                'posts' => $this->renderPartial('_load', [
                    'models' => $models,
                ]),
                'count' => (int)$count,
                'offset' => $offset + Post::PAGE_SIZE
            ];
        }
        else
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return mixed
     */
    public function actionSendArticle()
    {
        if(Yii::$app->request->isAjax)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $model = $this->getModel();

            $model->load(Yii::$app->request->post());

            if($model->validate())
            {
                Yii::$app->mailer->compose(['html' => '@common/modules/blog/mail/messageBlog-html', 'text' => '@common/modules/blog/mail/messageBlog-text'], ['model' => $model])
                    ->setFrom([Settings::getValue('bids-support-email-from') => 'Блог Task-On'])
                    ->setTo(Settings::getValue('article-email'))
                    ->setSubject("Блог. ".($model->form == 'theme'?"Предложить тему":"Статья"))
                    ->send();

                return ['success' => true];
            }
            else
            {
                return ActiveForm::validate($model);
            }
        }
        else
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    // Temp metod
    public function getModel()
    {
        $model = new \yii\base\DynamicModel([
                'name', 'email', 'message', 'form'
            ]);

        $model->addRule(['name', 'email', 'message'], 'required')
            ->addRule(['email'], 'email')
            ->addRule(['message', 'name', 'form'], 'string');

        return $model;
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $url
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelByUrl($url)
    {
        if (($model = Post::find()->where([
            'url' => $url,
            'active' => 1
        ])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
