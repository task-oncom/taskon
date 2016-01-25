<?php

use yii\helpers\Html;
use \common\components\zii\AdminGrid;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<?php echo AdminGrid::widget([
    'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
    'columns' => [
       	// ['class' => 'yii\grid\SerialColumn'],

    	'name',
		[
			'attribute' => 'created',
			'value' => function($model)
            {
				return date("d.m.Y H:i:s", $model->created);
			}
		],
		[
			'header' => 'Список пользователей',
			'value' => function($model) {
				return Html::a('Список пользователей', [
					"/testings/user-admin/manage", 
					"session" => \Yii::$app->request->get('session'),
					"group" => $model->id,
				]);
			},
			'format' => 'html',
		],
    ],
]); ?>