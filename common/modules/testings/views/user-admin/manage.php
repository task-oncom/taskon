<?php

use yii\helpers\Html;
use \common\components\zii\AdminGrid;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use common\modules\testings\models\User;
use common\modules\testings\models\UserGroup;
use common\modules\testings\models\SendHistory;
use common\modules\testings\models\Session;
use common\modules\testings\models\Passing;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$session = null;
if (\Yii::$app->request->get('session')) 
{
	$session = Session::findOne(\Yii::$app->request->get('session'));
}
?>

<p>
    <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    <?php if($session) : ?>
    	<?= Html::a('Разослать уведомления всем', ['/testings/session-admin/send-message-to-all', 'id' => $session->id], ['class' => 'btn btn-info']) ?>
    	<?= Html::a('История отправки дубликатов', ['/testings/send-history-admin/manage', 'session' => $session->id], ['class' => 'btn btn-info']) ?>
    	<?= Html::a('Импорт пользователей из XLS', ['/testings/session-admin/import-passings', 'id' => $session->id], ['class' => 'btn btn-info']) ?>
    <?php endif; ?>
</p>

<?php if (\Yii::$app->session->hasFlash('flash')) : ?>
	<div class="message"><span>
		<?php echo \Yii::$app->session->getFlash('flash'); ?>
	</span></div>
<?php endif; ?>

<?php 
$columns = [
	[
		'attribute'   => 'sex',
		'value'  => function($model)
        {
        	return User::$sex_list[$model->sex];
        },
		'filter' => User::$sex_list,
		'visible' => !\Yii::$app->request->get('session'),
	],
	'last_name',
	'first_name',
	'patronymic',
	'company_name',
	[
		'attribute' => 'filter_group_id',
		'value' => function($model)
        {
        	return $model->groupRelated->group->name;
        },
		'filter' => ArrayHelper::map(UserGroup::find()->where(['session_id' => \Yii::$app->request->get('session')])->all(), 'id', 'name')
	],
	[
		'attribute' => 'email',
		'value' => function($model)
        {
        	return Html::a($model->email, "mailto:" . $model->email);
        },
		'format' => 'html',
	],
	[
		'header' => 'Статус отправки',
		'attribute' => 'filter_history_status',
		'filter' => SendHistory::getStatusTitle(),
		'format' => 'raw',
		'value' => function($model) 
		{
			if($model->history)
			{
				return SendHistory::getStatusTitle($model->history->unisender_status);
			}
		},
	],
	[
		'header' => 'Время отправки',
		'value' => function($model) 
		{
			if($model->history)
			{
				return date('d.m.Y H:i:s', $model->history->sended);
			}
		},
	],
	[
		'attribute' => 'manager_id',
		'value' => function($model) 
		{
			if($model->manager)
			{
				return Html::a($model->manager->name, ["/users/user-admin/view", "id" => $model->manager_id]);
			}
			else
			{
				return "Пользователь удалён";
			}
		},
		'format' => 'html',
	],
	'tki'
];

$link = '';

if ($session) 
{		
	$marked = \Yii::$app->controller->getMarked($session->id);
	
	$qty = count($marked);

	$style = ($qty) ? '' : 'display:none;';

	$send = Html::a("Разослать выделенным ($qty)",
	   	['testings/session-admin/send-message-to-marked', 'id' => $session->id],  
	   	['style' => $style, 'id' => 'sendMarkup']
	);

	$clear = Html::a("[сброс]", 
	  	['update-mark', 'session' => $session->id], 
	  	['style' => $style, 'id' => 'resetMarkup']
	);

	$link = $send . ' ' . $clear . '<br><br>';

	echo $link;
	
	array_unshift(
		$columns, 
		[
			'class' => 'common\modules\testings\components\MarkBoxColumn',
			'updateUrl' => Url::to(['update-mark', 'session' => $session->id]),
		]
	);

	foreach($session->tests as $test) 
	{
		$columns = ArrayHelper::merge(
			$columns,
			[
				[
					'header' => $test->name,
					'value' => function($model) use($test)
					{
						$passing = Passing::find()->where([
							'test_id' => $test->id,
							'user_id' => $model->id
						])->one();

						if($passing)
						{
							return Html::a("Да", ["/testings/passing-admin/change-status", "user"=>$model->id, "test" => $test->id], ["title" => "Изменить на НЕТ"]);
						}
						else
						{
							return Html::a("Нет", ["/testings/passing-admin/change-status", "user"=>$model->id, "test" => $test->id], ["title" => "Изменить на ДА"]);
						}
					},
					'format' => 'html',
				]
			]			
		);
	}

	$buttons = [
		[
            'class' => \common\components\ColorActionColumn::className(),
	        'template' => '{send} {view} {update}',
	        'buttons' => [
	        	'send' => function ($url, $model, $key) use($session)
	        	{
			        return Html::a('<i class="fa fa-envelope fa-lg"></i>', Url::to(['testings/session-admin/send-message', 'id' => $session->id, 'user' => $model->id]), [
	                    'title' => 'Уведомить о тестировании',
	                    'data-toggle' => 'tooltip',
	                    'data-pjax' => '0',
	                ]);
			    },
	        ]
        ]
    ];

    $columns = ArrayHelper::merge($columns, $buttons);
}


echo AdminGrid::widget([
    'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
    'columns' => $columns,
]); 