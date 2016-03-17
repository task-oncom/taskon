<?php

use yii\helpers\Html;

use common\modules\bids\models\Bid;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\bids\models\SearchBid */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bids';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bid-index">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= \common\components\zii\AdminGrid::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',
            'name',
            'phone',
            'email:email',
            [
                'header' => 'Файлы',
                'format' => 'html',
                'value' => function($model) 
                {
                    $files = [];
                    if($model->files)
                    {
                        foreach ($model->files as $file) 
                        {
                            $files[] = Html::a($file->filename, $file->getUrl());
                        }
                    }
                    return implode('<br>', $files);
                }
            ],
            'text:ntext',
            [
                'attribute' => 'form',
                'filter' => Bid::$form_titles,
                'value' => function($model) 
                {
                    return ($model->form?Bid::$form_titles[$model->form]:null);
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:d.m.Y H:i:s']
            ],

            [
                'class' => 'common\components\ColorActionColumn',
                'template' => '{view} {update}',
            ]
        ],
    ]); ?>
</div>
