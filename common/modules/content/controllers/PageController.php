<?php

namespace common\modules\content\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use common\modules\content\models\CoContent;
use common\modules\school\models\Lessons;

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
            // if(!\Yii::$app->user->isGuest)
            //     return $this->redirect(\yii\helpers\Url::toRoute('/scoring/clients/cabinet'));
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
        
        $content = $model->lang->getFinishedContent();
        $this->meta_title = $model->metaTag->title;
        $this->meta_description = $model->metaTag->description;
        $this->meta_keywords = $model->metaTag->keywords;

        return $this->render('view', [
            'content' => $content,
            'model' => $model
        ]);
    }

}
