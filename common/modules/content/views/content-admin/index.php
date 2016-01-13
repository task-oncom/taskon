<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\content\models\SearchCoContent */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('content', 'Create Content'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= \common\components\zii\AdminGrid::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'name',
            //'url:url',
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function($data) {
                    return Html::a($data->url, 'http://soc-zaim.ru/'.$data->url, ['target' => '_blank', 'title' => 'Просмотреть как страницу видит пользователь', 'data-toggle'=>"tooltip"]);$data->category->name;
                }
            ],
            [
                'attribute' => 'category_id',
                'format' => 'text',
                'value' => function($data) {
                    if(!empty($data->category))
                        return $data->category->name;
                    else 'без категории';
                }
            ],
            [
                'class' => 'common\components\ColorActionColumn',
                'template' => '{copy} {update} {delete}',
                'buttons' => [

                    // Дмитрий Королев: хочется посмотреть как это будет вглядеть
                    'copy' => function ($url, $model, $key) {
                        return '<a href="'.Url::toRoute(['copypage', 'id' => $model->id]).'">'.Html::beginTag('i', [
                            'title' => "Копировать страницу",
                            'data-toggle' => 'tooltip',
                            'class' => 'fa fa-copy fa-lg'
                        ]) . Html::endTag('i') . '</a>';
                    },
                ],
            ],
        ],
    ]); ?>

