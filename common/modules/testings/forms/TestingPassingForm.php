<?php

return array(
    'activeForm' => array(
        'id' => 'testing-passing-form',
		//'enableAjaxValidation' => true,
		//'clientOptions' => array(
		//	'validateOnSubmit' => true,
		//	'validateOnChange' => true
		//)
    ),
    'elements' => array(
        'user_id' => array(
			'type' => 'dropdownlist',
			'items' => CHtml::listData(TestingUser::model()->findAll(),'id','fio'),
		),		
        'test_id' => array(
			'type' => 'dropdownlist',
			'items' => CHtml::listData(TestingTest::model()->findAll(),'id','name','session.name'),
		),
        //'is_passed' => array('type' => 'checkbox'),
        //'pass_date' => array('type' => 'text'),

    ),
    'buttons' => array(
        'submit' => array(
            'type'  => 'submit',
            'value' => $this->model->isNewRecord ? 'создать' : 'сохранить')
    )
);


