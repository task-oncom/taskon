<?php
use yii\helpers\ArrayHelper;

use common\modules\testings\models\Question;
use common\modules\testings\models\Answer;

$elements = [
	'question_id' => [
		'type' => 'dropdownlist',
		'items' => ArrayHelper::map(Question::find()->all(), 'id', 'text', 'test.name'),
	],
	'text' => ['type' => 'textarea'],
	'is_right' => [
		'type' => 'dropdownlist',
		'items' => Answer::$type_list,
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

