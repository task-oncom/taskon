<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

use common\modules\blog\models\Post;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\blog\models\SearchPost */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d.m.Y H:i:s']
            ],
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function($model)
                {
                    return Html::a($model->url, \Yii::$app->params['frontUrl'] . '/blog/' . $model->url, ['target' => '_blank', 'title' => 'Просмотреть как страницу видит пользователь', 'data-toggle'=>"tooltip"]);
                }
            ],
            [
                'attribute' => 'active',
                'filter' => Post::$active_title,
                'value' => function($model)
                {
                    return Post::$active_title[$model->active];
                }
            ],
            [
                'header' => 'Теги',
                'format' => 'raw',
                'value' => function($model)
                {
                    if($model->postTags)
                    {
                        $b = '<span class="label label-primary">';
                        $e = '</span>';

                        return $b . implode($e.' '.$b, array_keys(ArrayHelper::map($model->postTags, 'name', 'id'))) . $e;
                    }

                    return null;
                }
            ],
            [
                'attribute' => 'author_id',
                'value' => function($model)
                {
                    if($model->author)
                    {
                        return $model->author->surname . ' ' . $model->author->name;
                    }

                    return null;
                }
            ],

            [
                'class' => 'common\components\ColorActionColumn',
                'template' => '{statistics} {update} {delete}',
                'buttons' => [
                    'statistics' => function ($url, $model, $key) {
                        return '<a href="'.Url::toRoute(['/blog/statistics-admin/manage', 'id' => $model->id]).'">'.Html::beginTag('i', [
                            'title' => "Статистика",
                            'data-toggle' => 'tooltip',
                            'class' => 'fa fa-area-chart fa-lg'
                        ]) . Html::endTag('i') . '</a>';
                    },
                ],
            ],
        ],
    ]); ?>
</div>
