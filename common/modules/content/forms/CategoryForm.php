<?php

use yii\helpers\ArrayHelper;

return [
    'activeForm'=>[
        'id' => 'module-form',
    ],
    'elements'       => [
		'name'				=> ['type' => 'text', 'class' => 'form-control'],
		'url'			=> ['type' => 'text', 'class' => 'form-control'],
    ],
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];


