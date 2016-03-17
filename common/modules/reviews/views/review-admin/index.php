<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\reviews\models\SearchReviews */
/* @var $dataProvider yii\data\ActiveDataProvider */


?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('reviews', 'Create Reviews'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= \common\components\zii\AdminGrid::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'options' => [
            'id' => 'data-table',
        ],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'lang',
            'title',
            [
                'attribute' => 'text:ntext',
                'header' => 'Отзыв',
                'format' => 'raw',
                'value' => function($data) {
                    return strip_tags($data->text);
                },
                'options' => ['width' => '70%']
            ],
            // 'photo',
            // 'state',
            // 'date',
            // 'date_create',
            // 'priority',
            // 'email:email',
            // 'notification_date',
            // 'notification_send',
            // 'order',
            // 'attendant_products:ntext',
            // 'cat_id',
            // 'show_in_module',
            // 'rate_usability',
            // 'rate_loyality',
            // 'rate_profit',

            [
                'class' => 'common\components\ColorActionColumn',
                'template' => '{view}&nbsp;{update}&nbsp;{delete}',
                'contentOptions' => ['style' => 'width:100px;'],
            ],
        ],
    ]); ?>


