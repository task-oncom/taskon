<?php

use yii\helpers\Html;
use \common\components\zii\AdminGrid;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\faq\models\SearchFaq */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<p>
    <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
</p>

<?php echo AdminGrid::widget([
    'dataProvider' => $dataProvider,
	// 'filterModel' => $searchModel,
    'columns' => [
       // ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'name',
            'format' => 'raw',
            'value' => function($model)
            {
                return $model->name;
            }
        ],
		'start_date',
		'end_date',
		[
			'header' => 'Назначено тестов',
            'value' => function($model) 
            {
            	return $model->usersOverall;
           	},
		],
		[
			'header' => 'Сдано тестов',
            'value' => function($model) 
            {
            	return $model->usersPassed;
           	},
		],
		[
			'header' => 'Не сдано тестов',
            'value' => function($model) 
            {
            	return $model->usersFailed;
           	},
		],
		[
			'header' => 'Список тестов',
			'format' => 'html',
            'value' => function($model) 
            {
            	return Html::a("Список тестов", ["/testings/test-admin/manage", "session" => $model->id]);
           	},
		],
		[
			'header' => 'Список групп',
			'format' => 'html',
            'value' => function($model) 
            {
            	return Html::a("Список групп", ["/testings/user-admin/manage-group", "session" => $model->id]);
           	},
		],
		[
			'header' => 'Список пользователей',
			'format' => 'html',
            'value' => function($model) 
            {
            	return Html::a("Список пользователей", ["/testings/user-admin/manage", "session" => $model->id]);
           	},
		],
		[
			'header' => 'Список прохождений',
			'format' => 'html',
            'value' => function($model) 
            {
            	return Html::a("Список прохождений", ["/testings/passing-admin/manage", "session" => $model->id]);
           	},
		],
		[
			'header' => 'Статистика прохождений',
			'format' => 'html',
            'value' => function($model) 
            {
            	return Html::a("Статистика прохождений", ["/testings/passing-admin/statistics", "session" => $model->id]);
           	},
		],
        [
            'class' => 'common\components\ColorActionColumn',
	        'template' => '{view} {update}',
        ],
    ],
]); ?>