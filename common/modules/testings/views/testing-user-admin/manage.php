<?php

use yii\helpers\Html;
use \common\components\zii\AdminGrid;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use common\modules\testings\models\TestingUser;
use common\modules\testings\models\TestingUserGroup;
use common\modules\testings\models\TestingSendHistory;
use common\modules\testings\models\TestingSession;
use common\modules\testings\models\TestingPassing;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<p>
    <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
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
        	return TestingUser::$sex_list[$model->sex];
        },
		'filter' => TestingUser::$sex_list,
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
		'filter' => ArrayHelper::map(TestingUserGroup::find()->where(['session_id' => \Yii::$app->request->get('session')])->all(), 'id', 'name')
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
		'filter' => TestingSendHistory::getStatusTitle(),
		'format' => 'raw',
		'value' => function($model) 
		{
			if($model->history)
			{
				return TestingSendHistory::getStatusTitle($model->history->unisender_status);
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

if (\Yii::$app->request->get('session')) 
{
	$session = TestingSession::findOne(\Yii::$app->request->get('session'));
	if ($session) 
	{		
		$marked = \Yii::$app->controller->getMarked($session->id);
		
		$qty = count($marked);

		$style = ($qty) ? '' : 'display:none;';

		$send = Html::a("Разослать выделенным ($qty)",
		   	['/testings/testing-session-admin/send-message-to-marked', 'id' => $session->id],  
		   	['style' => $style, 'id' => 'sendMarkup']
		);

		$clear = Html::a("[сброс]", 
		  	['resetMark', 'session' => $session->id], 
		  	['style' => $style, 'id' => 'resetMarkup']
		);

		$link = $send . ' ' . $clear;
		
		// array_unshift(
		// 	$columns, 
		// 	[
		// 		'class' => 'MarkBoxColumn',
		// 		'update_url' => Url::to(['update-mark', 'session' => $session->id]),
		// 	]
		// );

		foreach($session->tests as $test) 
		{
			$columns = ArrayHelper::merge(
				$columns,
				[
					[
						'header' => $test->name,
						'value' => function($model) use($test)
						{
							$passing = TestingPassing::find()->where([
								'test_id' => $test->id,
								'user_id' => $model->id
							])->one();

							if($passing)
							{
								return Html::a("Да", ["/testings/testing-passing-admin/change-status", "user"=>$model->id, "test" => $test->id], ["title" => "Изменить на НЕТ"]);
							}
							else
							{
								return Html::a("Нет", ["/testings/testing-passing-admin/change-status", "user"=>$model->id, "test" => $test->id], ["title" => "Изменить на ДА"]);
							}
						},
						'format' => 'html',
					]
				]
			);
		}

		// $this->tabs = array(
		// 	'разослать уведомления всем' => $this->createUrl('/testings/testingSessionAdmin/sendMessageToAll',array('id'=>$session->id)),
		// 	'история отправки дубликатов' => $this->createUrl('/testings/testingSendHistoryAdmin/manage', array('session'=>$session->id)),
		// 	'импорт пользователей из CSV' => $this->createUrl(
		// 		'/testings/testingSessionAdmin/importPassings',
		// 		array('id'=>$session->id)
		// 	),
		// );

		// $buttons = array(
		// 	'class' => 'CButtonColumn',
		// 	'template' => '{sendEmail}{view}{update}',
		// 	'buttons'         => array(
		// 		'sendEmail' => array(
		// 			'url'      => 'array("/testings/testingSessionAdmin/sendMessage","id"=>'.$session->id.',"user"=>$data->id)',
		// 			'imageUrl' => '/images/icons/mail.png',
		// 			'options'  => array(
		// 				'title' => 'Уведомить о тестировании',
		// 			),
		// 		),
		// 	),
		// );
	}
}


echo AdminGrid::widget([
    'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
    'columns' => $columns
]); 



// $this->widget('AdminGrid', array(
// 	'id' => 'testing-user-grid',
// 	'dataProvider' => $model->search(),
// 	'filter' => $model,
// 	'columns' => $columns,
// 	'template' => "$link {pagerSelect}{summary}<br/>{pager}<br/>{items}<br/>{pager}",
// ));