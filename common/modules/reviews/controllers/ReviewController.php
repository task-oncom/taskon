<?php

namespace common\modules\reviews\controllers;
use common\modules\reviews\models\SearchReviews;

class ReviewController extends \common\components\BaseController
{
    public $layout = '//main-short';

    public static function actionsTitles() {
        return [
            "Index"           	=> "Редактирование группы",
            "View"             	=> "Просмотр группы",
        ];
    }

    public function actionIndex()
    {
        \common\components\AppManager::setSEO('-review');

        \yii::$app->controller->breadcrumbs = [
            'Отзывы клиентов'
        ];

        $this->meta_title = 'Отзывы, полученные от клиентов СоцЗайм';
        $this->meta_description = 'Реальные отзывы от клиентов, пользующихся сервисом СоцЗайм ';
        $this->meta_keywords = 'отзывы, клиенты, о компании';

        $searchModel  = new SearchReviews();
        $search = \Yii::$app->request->queryParams;
        $search['show_in_module'] = 1;
        $search['state'] = 'active';
        $dataProvider = $searchModel->search($search);

        return $this->render('index', ['$searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionView()
    {
        return $this->render('view');
    }

}
