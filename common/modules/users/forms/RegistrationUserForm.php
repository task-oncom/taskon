<?php

use common\modules\users\models\User;
$elements = [
    'errors_reg' => '<div class="errors-reg"></div>',
    'name'	=> ['type' => 'text', 'placeholder'=>"Ваше имя*", 'options'=>['label'=>false], 
        'inputOptions'=>['class'=>'input_st field-input required alphanumeric']],
    'email' => ['type' => 'text', 'placeholder'=>"E-mail*", 'options'=>['label'=>false],
        'inputOptions'=>['class'=>'input_st field-input required alphanumeric']],
    'password' => ['type' => 'password', 'placeholder'=>"Придумайте пароль*", 'options'=>['label'=>false],
        'inputOptions'=>['class'=>'input_st field-input required alphanumeric']],
    'password_c' => ['type' => 'password', 'placeholder'=>"Повторите пароль*", 'options'=>['label'=>false],
        'inputOptions'=>['class'=>'input_st field-input required alphanumeric']],
    'phone' => ['type' => 'text', 'placeholder'=>"Телефон", 'options'=>['label'=>false],
        'inputOptions'=>['class'=>'input_st field-input required alphanumeric']],
    'teltext' => '<div class="teltext-block">Для участие в проводимых конкурсах укажите Ваш реальный номер телефона, 
        что бы мы смогли вас оповестить о результатах</div>',
    'occupation' => [
        'type' => 'dropdownlist',
        'items' => User::$occupation_list,
        'placeholder'=>"Род занятий", 
        'options'=>['label'=>false],
        'empty'=>'Чем вы занимаетесь',
        'inputOptions'=>['class'=>'input_st field-input required alphanumeric']
    ],
    'button_submit' => '<a href="javascript:void(0)" class="submit_form_reg">
        <div class="save-button sh_bt_send">
            Записаться
        </div>
        </a>',
    'login_link' => '<a class="login_form_link" href="javascript:void(0)">Уже есть аккаунт?</a>',
    'text-reg' => '<span class="popup_text">Проходя регистрацию вы подтверждаете<br>
            <a href="javascript:void(0)">согласие на обработку персональных данных.</a></span>',

];


return [
    'activeForm'=>[
        'id' => 'sh_reg_form',
        'options' => [
            'enctype' => 'multipart/form-data'
        ],
    ],
    'elements'       => $elements,
    /*'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Записаться', 'class'=>"save-button sh_bt_send"]
    ]*/
];

