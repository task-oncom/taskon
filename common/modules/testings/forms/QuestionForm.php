<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use common\modules\testings\models\Test;
use common\modules\testings\models\Theme;
use common\modules\testings\models\Question;

$files = '';

if($model->files)
{
	foreach ($model->files as $file) 
	{
		$files .= Html::img($file->getUrl(), ['width' => 150]) . ' ';
	}
}

$elements = [
	'test_id' => [
		'type' => 'dropdownlist',
		'items' => ArrayHelper::map(Test::find()->all(), 'id', 'name', 'session.name'),
	],
	'theme_id' => [
		'type' => 'dropdownlist',
		'items' => ArrayHelper::map(Theme::find()->all(), 'id', 'name'),
	],
	'text' => ['type' => 'textarea'],
	'is_active' => [
		'type' => 'dropdownlist',
		'items' => Question::$active_list,
	],
	'type' => [
		'type' => 'dropdownlist',
		'items' => Question::$type_list,
	],
	$files,
	'filesUpload[]' => [
		'type'      => 'file',
		'fileOptions'	=> [
			'multiple' => true,
			'accept' => 'image/*',
		]
	],
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
        'options' => [
            'enctype' => 'multipart/form-data'
        ],
    ],
    'elements'       => $elements,
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];

