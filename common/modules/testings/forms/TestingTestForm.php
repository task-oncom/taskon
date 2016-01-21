<?php
use yii\helpers\ArrayHelper;

use common\modules\testings\models\TestingSession;

$elements = [
	'session_id' => [
		'type' => 'dropdownlist',
		'items' => ArrayHelper::map(TestingSession::find()->all(), 'id', 'name'),
	],
	'name' => ['type' => 'text'],
	'minutes' => ['type' => 'text'],
	'questions' => ['type' => 'text'],
	'pass_percent' => ['type' => 'text'],
	'attempt' => ['type' => 'text']
];

// если id сессии указан одним из параметров запроса - не показываем это поле
if (\Yii::$app->request->get('session')) 
{
	$elements['session_id'] = [
		'type' => 'hidden',
		'value' => \Yii::$app->request->get('session'),
	];
}

return [
    'activeForm'=>[
        'id' => 'testing-test-form',
    ],
    'elements'       => $elements,
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];