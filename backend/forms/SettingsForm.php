<?php

use yii\helpers\ArrayHelper;

return [
    'activeForm'=>[
        'id' => 'module-form',
    ],
    'elements'       => [
		'module_id'			=> [
            'type'  => 'dropdownlist',
            'items' => \common\components\AppManager::getModulesList(),
			'class' => 'form-control',
    	],
		'code'				=> ['type' => 'text', 'class' => 'form-control'],
		'name'				=> ['type' => 'text', 'class' => 'form-control'],
		'value'				=> ['type' => 'text', 'class' => 'form-control'],
		'element'			=> [
            'type'  => 'dropdownlist',
            'items' => ['editor', 'text', 'textarea'],
			'class' => 'form-control',
    	],
		'description'		=> ['type' => 'text', 'class' => 'form-control'],
		'hidden' 			=> ['type' => 'checkbox'],
    ],
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];


