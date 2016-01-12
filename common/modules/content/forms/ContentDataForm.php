<?php

use yii\helpers\ArrayHelper;

return [
    'activeForm'=>[
        'id' => 'controller-form',
        'class' => 'ActiveForm',
		'options' => ['class' => 'form-horizontal'],
		'fieldConfig' => [
//			'template' => '<div class="form-group">{label}<div class="col-md-9">{input}</div><div class="col-md-9">{error}</div></div>',
			'labelOptions' => ['class' => 'col-md-3 control-label'],
		],
        'enableAjaxValidation' => false,
// 	'htmlOptions'=>['class'=>'registr'),
    ],
    'elements'       => [
		'content_id' => [
			'type'  => 'dropdownlist',
            'items' => ArrayHelper::map(\common\modules\content\models\CoContent::find()->all(), 'id', 'name'),
			'class' => 'form-control',
		],
		'title'				=> ['type' => 'text', 'class' => 'form-control'],
		'short_description'	=> ['type' => 'textarea', 'class' => 'form-control'],
		'description'		=> ['type' => 'textarea', 'class' => 'form-control'],
    ],
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];


