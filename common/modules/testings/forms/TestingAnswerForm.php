<?php
use yii\helpers\ArrayHelper;

use common\modules\testings\models\TestingQuestion;
use common\modules\testings\models\TestingAnswer;

$elements = [
	'question_id' => [
		'type' => 'dropdownlist',
		'items' => ArrayHelper::map(TestingQuestion::find()->all(), 'id', 'text', 'test.name'),
	],
	'text' => ['type' => 'textarea'],
	'is_right' => [
		'type' => 'dropdownlist',
		'items' => TestingAnswer::$type_list,
	],
];

// если id указан одним из параметров запроса - не показываем это поле
if (\Yii::$app->request->get('question')) 
{
	$elements['question_id'] = [
		'type' => 'hidden',
		'value' => \Yii::$app->request->get('question'),
	];
}

return [
    'activeForm'=>[
        'id' => 'testing-answer-form',
    ],
    'elements'       => $elements,
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];

