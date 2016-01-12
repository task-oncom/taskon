<?php

use yii\helpers\Html;
use \common\components\zii\AdminGrid;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\faq\models\SearchFaq */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('faq', 'Create Faq'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo AdminGrid::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'lang',
            'name',
            // 'last_name',
            // 'patronymic',
            // 'phone',
            'email:email',
            // 'cat_id',
            [
                'attribute' => 'question',
                'format' => 'raw',
                'value' => function($model) {return $model->shortQuestion;},
                //'contentOptions' => ['width' => '40%']
            ],
            [
                'attribute' => 'answer',
                'contentOptions' => ['width' => '40%'],
                'format' => 'raw',
                'value' => function($model) {return $model->shortAnswer;},
            ],
            [
                'attribute' => 'is_published',
                'format' => 'raw',
                'value' => function($model) {return $model->is_published ? 'Опубликовано' : 'Неопубликовано';},
            ],
            // 'welcome',
            // 'notification_date',
            // 'notification_send',
            // 'order',
            // 'show_in_module',
            // 'view_count',
            // 'url:url',
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'common\components\ColorActionColumn',
		        'template' => '{update} {delete}',
                'contentOptions' => ['style' => 'width:60px;', 'align' => 'center'],
            ],
        ],
    ]); ?>

