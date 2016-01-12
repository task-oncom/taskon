<?php

use yii\helpers\ArrayHelper;

return [
    'activeForm'=>[
        'id' => 'module-form',
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
        'module_id'			=> [
            'type'  => 'hidden',
        ],
        'code'				=> ['type' => 'hidden', 'class' => 'form-control'],
        'name'				=> ['type' => 'text', 'class' => 'form-control'],
        'value'				=> ['type' => 'text', 'class' => 'form-control'],
        'element'			=> [
            /*'type'  => 'dropdownlist',
            'items' => ['editor','text','textarea'],
            'class' => 'form-control',*/
            'type' => 'hidden',
        ],
        'description'		=> ['type' => 'hidden', 'class' => 'form-control'],
        'hidden' 			=> ['type' => 'hidden'],
//		'created_at'				=> ['type' => 'text', 'class' => 'form-control'],
//		'updated_at'				=> ['type' => 'text', 'class' => 'form-control'],
    ],
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];

