<?php

use yii\helpers\ArrayHelper;

return [
    'activeForm'=>[
        'id' => 'answer-form',
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
        'admin_id'              => [
            'type' => 'dropdownlist',
            'items' => ArrayHelper::map(\common\modules\users\models\User::find()->all(),'id','fio'),
            'empty' => 'Оператор',
        ],
        'answer'			=> ['type' => 'textarea', 'class' => 'form-control'],
        'state'				=> [
            'type' => 'dropdownlist',
            'items' => ['active','hidden'],
            'class' => 'form-control'
        ],
    ],
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];


