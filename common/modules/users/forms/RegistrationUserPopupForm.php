<?php

use common\modules\users\models\User;
$elements = [
    
    'open_form_reg' => '<div class="registration_form_left form_resp">',
    'errors_reg' => '<div class="errors-reg_popup"></div>',
    'name'	=> ['type' => 'text', 'placeholder'=>"Ваше имя*", 'options'=>['label'=>false], 
        'inputOptions'=>['class'=>'input_st field-input required alphanumeric']],
    'email' => ['type' => 'text', 'placeholder'=>"E-mail*", 'options'=>['label'=>false],
        'inputOptions'=>['class'=>'input_st field-input required email']],
    'password' => ['type' => 'password', 'placeholder'=>"Придумайте пароль*", 'options'=>['label'=>false],
        'inputOptions'=>['class'=>'input_st field-input required email']],
    'password_c' => ['type' => 'password', 'placeholder'=>"Повторите пароль*", 'options'=>['label'=>false],
        'inputOptions'=>['class'=>'input_st field-input required email']],
    'phone' => ['type' => 'text', 'placeholder'=>"Телефон", 'options'=>['label'=>false],
        'inputOptions'=>['class'=>'input_st field-input required email']],
    'occupation' => [
        'type' => 'dropdownlist',
        'items' => User::$occupation_list,
        'placeholder'=>"Чем вы занимаетесь", 
        'options'=>['label'=>false],
        'empty'=>'Чем вы занимаетесь',
        'inputOptions'=>['class'=>'input_st field-input required alphanumeric']
    ],
    'button_submit' => '<a href="javascript:void(0)" class="submit_form_reg_popup">
        <div class="save-button popup_bt_send save-button-lg">
            Начать обучение
        </div>
        </a>',
    'close_form_reg' => '</div>',
    'how_work' =>' <div class="registration_form_right form_resp">
                    <span class="popup__title3">Как это работает?</span>
                    <p>Каждую неделю мы будем высылать тебе по 1 видео-уроку, который позволит освоить данную тему. Просмотр уроков и бонусных материалов - бесплатный!</p>
                    <div class="registration_form_img">
                        <br>
                        <img src="/images/registration_form_img.jpg" alt="">
                        <br>
                        <br>
                    </div>
                    <div class="spec_propotition">
                        <p><strong>Более того, самому активному пользователю мы предусмотрели <span class="red_text">приз в размере 15 000 руб</span>. Все что надо, чтобы получить награду, это смотреть ролики и проявлять активность.</strong></p>
                    </div>
                    <br>
                    <br>
                    <p>Если у Вас уже есть аккаунт <a href="#registration_form2" class="popup-form">Войти</a></p>
                </div>
                <div class="clear"></div>'
];


return [
    'activeForm'=>[
        'id'=>'reg_form_popup',
        'options' => [
            'enctype' => 'multipart/form-data',
            'class' =>'validreg_form2',
        ],
    ],
    'elements'       => $elements,
];

