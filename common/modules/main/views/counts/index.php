<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\main\models\CountsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


?>
<div class="counts-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить счетчик', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= \common\components\zii\AdminGrid::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'count:ntext',
            /*'created_at',
            'updated_at',*/

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
