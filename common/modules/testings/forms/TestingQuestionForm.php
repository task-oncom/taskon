<?php
use yii\helpers\ArrayHelper;

use common\modules\testings\models\TestingTest;
use common\modules\testings\models\TestingTheme;
use common\modules\testings\models\TestingQuestion;

$elements = [
	'test_id' => [
		'type' => 'dropdownlist',
		'items' => ArrayHelper::map(TestingTest::find()->all(), 'id', 'name', 'session.name'),
	],
	'theme_id' => [
		'type' => 'dropdownlist',
		'items' => ArrayHelper::map(TestingTheme::find()->all(), 'id', 'name'),
	],
	'text' => ['type' => 'textarea'],
	'is_active' => [
		'type' => 'dropdownlist',
		'items' => TestingQuestion::$active_list,
	],
	'type' => [
		'type' => 'dropdownlist',
		'items' => TestingQuestion::$type_list,
	],
	// 'files' => [
	// 	'type'      => 'file_manager',
	// 	'data_type' => 'any',
	// 	'title'     => 'Файлы для скачивания ',
	// 	'tag'       => 'files'
	// ],
	// 'Ответы' => [
	// 	'title'	=> 'Ответы',
	// 	'type'		=> 'answers',
	// ],
];

// если id сессии указан одним из параметров запроса - не показываем это поле
if (\Yii::$app->request->get('test')) 
{
	$elements['test_id'] = array(
		'type' => 'hidden',
		'value' => \Yii::$app->request->get('test'),
	);
}

return [
    'activeForm'=>[
        'id' => 'testing-question-form',
    ],
    'elements'       => $elements,
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];

