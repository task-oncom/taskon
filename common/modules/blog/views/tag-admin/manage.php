<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\blog\models\SearchPostTag */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Post Tags';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-tag-index">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'name',

            [
                'class' => 'common\components\ColorActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
</div>
