<?php

$js = <<<JS
$('#retest').click(function(){
	$('#mistake-retest').val(true);
	$('#testing-answer-form').submit();
});
JS;

\Yii::$app->getView()->registerJs($js, \yii\web\View::POS_END, 'formLoad');

return [
    'activeForm'=>[
        'id' => 'testing-answer-form',
    ],
    'elements' => [
		'description' => ['type' => 'textarea'],
		'is_expert_agreed' => ['type' => 'checkbox'],
		'retest' => ['type' => 'hidden'],
		// 'files' => array(
		// 	'type'      => 'file_manager',
		// 	'data_type' => 'any',
		// 	'title'     => 'Файлы для скачивания ',
		// 	'tag'       => 'files'
		// ),
	],
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить'],
        'retest' => ['type' => 'info', 'value' => 'Назначить пересдачу'],
    ]
];


