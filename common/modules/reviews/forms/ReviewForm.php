<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\modules\reviews\models\Reviews;

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
		'lang'              => [
            'type' => 'dropdownlist',
            'items' => ArrayHelper::map(\common\modules\languages\models\Languages::find()->all(),'code','name')
        ],
        'user_id'              => [
            'type' => 'dropdownlist',
            'items' => ArrayHelper::map(\common\modules\scoring\models\ScClient::find()->all(),'id','fullName'),
        ],
        'date'				=> ['type' => 'date', 'class' => 'form-control',],
        'text'				=> ['type' => 'textarea', 'class' => 'form-control'],
        '<a id="doAnswer" style="cursor: pointer;" onclick="$(this).next().next().slideToggle(); return false;">Ответить</a>' .
        '<br><div style="display: none;">',
        'admin_id'              => [
            'type' => 'dropdownlist',
            'items' => ArrayHelper::map(\common\modules\users\models\User::find()->all(),'id','fio'),
            'empty' => 'Оператор',

        ],
        'answer'			=> ['type' => 'textarea', 'class' => 'form-control'],
        '</div>',
        'state'				=> [
            'type' => 'dropdownlist',
            'items' => ['active','hidden'],
            'class' => 'form-control'
        ],
        'rate_usability'			=> [
            'type' => 'dropdownlist',
            'items' => Reviews::getSource('rate_usability'),
            'class' => 'form-control'
        ],
        'rate_loyality'				=> [
            'type' => 'dropdownlist',
            'items' => Reviews::getSource('rate_loyality'),
            'class' => 'form-control'
        ],
        'rate_profit'				=> [
            'type' => 'dropdownlist',
            'items' => Reviews::getSource('rate_profit'),
            'class' => 'form-control'
        ],
    ],
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];


