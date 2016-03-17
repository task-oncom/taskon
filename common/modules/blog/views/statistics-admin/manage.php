<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\sessions\models\SearchSession */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sessions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="session-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'PHPSESSID',
            [
                'attribute' => 'user_id',
                'value' => function($model)
                {
                    if($model->user_id)
                    {
                        return $model->user->surname.' '.$model->user->name;
                    }
                    return null;
                }
            ],
            'ip',
            [
                'header' => 'Общее время просмотра',
                'value' => function($model)
                {
                    return date('H:i:s', mktime(0, 0, $model->time));
                }
            ],

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
