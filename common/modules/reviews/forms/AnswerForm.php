<?php

use yii\helpers\ArrayHelper;

return [
    'activeForm'=>[
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


