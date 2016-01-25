<?php

use yii\helpers\Html;
use \common\components\zii\AdminGrid;

use common\modules\testings\models\Session;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$session_id = null;
$session = \Yii::$app->request->get('session');
if($session) 
{
	$session = Session::find()->where(['id' => $session])->one();
	if ($session) 
	{
		$session_id = $session->id;
	}
}

?>

<p>
    <?= Html::a('Добавить', ['create', 'session' => $session_id], ['class' => 'btn btn-success']) ?>
</p>

<?php echo AdminGrid::widget([
    'dataProvider' => $dataProvider,
	// 'filterModel' => $searchModel,
    'columns' => [
       // ['class' => 'yii\grid\SerialColumn'],

    	[
			'attribute' => 'session.name',
			'visible' => !$session_id,
			'format' => 'raw',
            'value' => function($model) 
            {
            	return Html::a($model->session->name, ["/testings/session-admin/view", "id" => $model->session_id]);
           	},
		],
		'name',
		'minutes',
		'questions',
		'pass_percent',
		'attempt',
		[
			'header' => 'Список вопросов',
			'value' => function($model) 
			{
				if($model->mix) 
				{
					return;
				}
				
				return Html::a("Список вопросов", ["/testings/question-admin/manage", "test" => $model->id]);
			},
			'format' => 'raw',
		],
		[
			'header' => 'Список тем',
			'value' => function($model) 
			{
				if($model->mix) 
				{
					return;
				}
				
				return Html::a("Список тем", ["/testings/theme-admin/manage", "test" => $model->id]);
			},
			'format' => 'html',
		],
        [
            'class' => 'common\components\ColorActionColumn',
	        'template' => '{view} {update}',
        ],
    ],
]); ?>

