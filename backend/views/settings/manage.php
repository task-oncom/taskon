<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchSettings */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Добавить параметр'), Url::toRoute(['create', 'module_id'=>$module_id]), ['class' => 'btn btn-success']) ?>
    </p>

    <?= \common\components\zii\AdminGrid::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'module_id',
            'code',
            'name',
            'value:ntext',
            // 'element',
            // 'hidden',
            // 'description',

            [
				'class' => 'common\components\ColorActionColumn',
				'urlCreator' => function($action, $model, $key, $index) {
					return \yii\helpers\Url::toRoute([$action, 'module_id' => $model->module_id, 'id' => $model->id]);
				}
			],
        ],
    ]); ?>


