<?php

$buttons = array(
	'submit' => array(
		'type'  => 'submit',
		'value' => 'сохранить',
	),
	'retest' => array(
		'type'  => 'submit',
		'value' => 'назначить пересдачу',
		//'style' => 'width: 400px;',
	)
);


return array(
    'activeForm' => array(
        'id' => 'testing-mistake-form',
		//'enableAjaxValidation' => true,
		//'clientOptions' => array(
		//	'validateOnSubmit' => true,
		//	'validateOnChange' => true
		//)
    ),
    'elements' => array(
        //'passing_id' => array('type' => 'text'),
        'description' => array('type' => 'textarea'),
        'is_expert_agreed' => array('type' => 'checkbox'),
		'files' => array(
			'type'      => 'file_manager',
			'data_type' => 'any',
			'title'     => 'Файлы для скачивания ',
			'tag'       => 'files'
		),		
        //'create_date' => array('type' => 'text'),

    ),
    'buttons' => $buttons,
);


