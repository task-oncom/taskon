<?php
$socButtons = \common\modules\eauth\widgets\SocialWidget::widget(['action' => '/site/login']);

$elements = [
    'open_form_login' => '<div class="registration_form_left form_resp">',
    'errors_login' => '<div class="errors_login_popup"></div>',
    'username' => ['type' => 'text', 'placeholder'=>"E-mail", 'options'=>['label'=>false],
        'inputOptions'=>['class'=>'input_st field-input required email']],
    'password' => ['type' => 'password', 'placeholder'=>"Пароль", 'options'=>['label'=>false],
        'inputOptions'=>['class'=>'input_st field-input required email']],
    'button_submit' => '<a href="javascript:void(0)" class="submit_form_login_popup">
        <div class="save-button popup_bt_send save-button-lg">
            Войти
        </div>
        </a>',
    'close_form_login' => '</div>',
    'soc_block_open' =>'<div class="registration_form_right50 form_resp">
                            <p>Войти через</p>
                            <br>
                            <div class="button_social">',
    'soc_buttons' => $socButtons,
    'soc_block_close' =>'</div>
                    </div>
                    <div class="clear"></div>
                    <br>
                    <p>Если у Вас еще нет аккаунта <a href="#registration_form" class="popup-form">Зарегистрируйтесь.</a></p>'
];

                
return [
    'activeForm'=>[
        'id' => 'login_form_popup',
        'options' => [
            'enctype' => 'multipart/form-data',
            'class' =>'validreg_form2',
        ],
    ],
    'elements'       => $elements,
];

