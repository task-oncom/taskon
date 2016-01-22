<?php

return array(
    'activeForm' => array(
        'id' => 'testing-user-form',
		//'enableAjaxValidation' => true,
		//'clientOptions' => array(
		//	'validateOnSubmit' => true,
		//	'validateOnChange' => true
		//)
    ),
    'elements' => array(
        'sex' => array(
			'type'  => 'dropdownlist',
            'items' => TestingUser::$sex_list
		),
        'last_name' => array('type' => 'text'),
        'first_name' => array('type' => 'text'),
        'patronymic' => array('type' => 'text'),
        'company_name' => array('type' => 'text'),
		'city' => array('type' => 'text'),
        'email' => array('type' => 'text'),
		'tki' => array('type' => 'text'),
		'region' => array('type' => 'text'),
    ),
    'buttons' => array(
        'submit' => array(
            'type'  => 'submit',
            'value' => $this->model->isNewRecord ? 'создать' : 'сохранить')
    )
);


