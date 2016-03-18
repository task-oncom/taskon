<?php

use yii\helpers\ArrayHelper;

return [
    'activeForm'=>[
        'id' => 'module-form',
    ],
    'elements'       => [
        'module_id'			=> [
            'type'  => 'hidden',
        ],
        'code'				=> ['type' => 'hidden', 'class' => 'form-control'],
        'name'				=> ['type' => 'text', 'class' => 'form-control'],
        'value'				=> ['type' => 'text', 'class' => 'form-control'],
        'element'			=> [
            'type' => 'hidden',
        ],
        'description'		=> ['type' => 'hidden', 'class' => 'form-control'],
        'hidden' 			=> ['type' => 'hidden'],
    ],
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];

