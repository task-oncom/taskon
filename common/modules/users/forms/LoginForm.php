<?php

/*return array(
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
);*/

$elements = [
    'errors_login' => '<div class="errors_login"></div>',
    'username' => ['type' => 'text', 'placeholder'=>"E-mail", 'options'=>['label'=>false],
        'inputOptions'=>['class'=>'input_st field-input required alphanumeric']],
    'password' => ['type' => 'password', 'placeholder'=>"Пароль", 'options'=>['label'=>false],
        'inputOptions'=>['class'=>'input_st field-input required alphanumeric']],
    'button_submit' => '<a href="javascript:void(0)" class="submit_form_login">
        <div class="save-button sh_bt_send">
            Войти
        </div>
        </a>',
    'registration_link' => '<a class="reg_form_link" href="javascript:void(0)">Регистрация</a>',  
];


return [
    'activeForm'=>[
        'id' => 'login_form',
        'options' => [
            'enctype' => 'multipart/form-data'
        ],
    ],
    'elements'       => $elements,
];

