<?php

namespace common\modules\content\controllers;
use yii\web\BadRequestHttpException;
use common\modules\content\models\CoContent;

class PageController extends \common\components\BaseController
{
    public static function actionsTitles(){
        return [
            'View'	 		  => 'Просмотр контента',
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView($id = null, $page = null)
    {
        if(empty($id) && empty($page)) {
            if(!\Yii::$app->user->isGuest)
                return $this->redirect(\yii\helpers\Url::toRoute('/scoring/clients/cabinet'));
            $page = '/';
        }
        if(!empty($id))
        {
            $model = CoContent::findOne($id);
        }
        else
        {
            $model = CoContent::findOne(['url' => $page]);
        }

        $content = $model->getContent();
        $this->meta_title = $model->metaTags->title;
        $this->meta_description = $model->metaTags->description;
        $this->meta_keywords = $model->metaTags->keywords;

        return $this->render('view', ['content'=>$content]);
    }

}
