<?php

return array(
    'activeForm' => array(
        'id' => 'testing-gamma-form',
		//'enableAjaxValidation' => true,
		//'clientOptions' => array(
		//	'validateOnSubmit' => true,
		//	'validateOnChange' => true
		//)
    ),
    'elements' => array(
        'name' => array('type' => 'text'),
        'type' => array(
			'type' => 'dropdownlist',
			'items' => TestingGamma::$type_list,
		),

    ),
    'buttons' => array(
        'submit' => array(
            'type'  => 'submit',
            'value' => $this->model->isNewRecord ? 'создать' : 'сохранить')
    )
);


