<?php

$buttons = array(
	'submit' => array(
		'type'  => 'submit',
		'value' => 'сохранить',
	),
);


return array(
    'activeForm' => array(
        'id' => 'bulkmail-account-form',
		//'enableAjaxValidation' => true,
		//'clientOptions' => array(
		//	'validateOnSubmit' => true,
		//	'validateOnChange' => true
		//)
    ),
    'elements' => array(
        'server' => array('type' => 'text'),
        'port' => array('type' => 'text'),
        'smtp_secure' => array('type' => 'dropdownlist', 'items' => array('' => '', 'ssl' => 'SSL', 'tls' => 'TLS')),
        'email' => array('type' => 'text'),
        'username' => array('type' => 'text'),
        'password' => array('type' => 'text'),
        //'create_date' => array('type' => 'text'),

    ),
    'buttons' => $buttons,
);


