<?php
$form = include "UserForm.php";

$form['activeForm']['enableAjaxValidation'] = false;

$form['elements'] = array(
    'login'    => $form['elements']['login'],
    'captcha'  => $form['elements']['captcha']
);

$form['buttons']['submit']['value'] = 'Далее';

return $form;