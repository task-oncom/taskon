<?php

return array(
    'activeForm'=>array(
        'id' => 'user-form',
        'class' => 'CActiveForm',
        'enableAjaxValidation' => false,
    ),
    'elements'       => array(
        'email'      => array('type' => 'text', 'class' => 'border_for_login_form'),
        'password'   => array('type' => 'password', 'class' => 'border_for_login_form login'),
    ),
    'buttons' => array(
        'submit' => array('type' => 'submit', 'value' => 'Войти')
    )
);