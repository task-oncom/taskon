<?php

$form = include "UserForm.php";

$form['activeForm']['enableAjaxValidation'] = false;

$form['elements'] = array(
    'send_email'    => array('type'=>'text','value'=>$this->model->email),
	'password'    => array('type' => 'password'),
	'password_c'    => array('type' => 'password'),
	'generate_new' => array('type' => 'checkbox'),
);

$form['buttons'] = array(
	'submit' => array('type' => 'submit', 'value' => 'Отправить')
);

return $form;