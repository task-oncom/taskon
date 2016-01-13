<?php
/* @var $this yii\web\View */
use \yii\grid\GridView;
use \yii\helpers;
use himiklab\sortablegrid\SortableGridView;
?>
<h1>category/index</h1>

<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<?
echo helpers\Html::a('Add new category', [helpers\Url::to('create')], ['class'=>'btn btn-primary']);

echo SortableGridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        // Simple columns defined by the data contained in $dataProvider.
        // Data from the model's column will be used.
        'id',
        'total_balls',
        // More complex one.
        [
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'label' => 'Name',
            'format' => 'text',
            'attribute' => 'name',
            /*
            'value' => function ($data) {
                return $data->name; // $data['name'] for array data, e.g. using SqlDataProvider.
            },
            */
        ],
        [
            'class' => 'common\components\ColorActionColumn',
            'header' => 'Property List',
            'buttons' => [
                'property' => function ($url, $model, $key) {
                    //return $model->status === 'editable' ? Html::a('Properties', [$url, 'id'=>$key]) : '';
                    $url = helpers\Url::to(['/scoring/property/index', 'category_id'=>$key]);
                    return helpers\Html::a('Properties', $url);
                }
            ],
            'template' => '{property}',
        ],
    ],
]);
?>
