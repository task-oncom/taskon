<?php
$form = include "UserForm.php";

$form['activeForm']['enableAjaxValidation'] = false;

unset($form['elements']['status']);
unset($form['elements']['role']);
unset($form['elements']['send_email']);

$form['buttons']['submit']['value'] = 'Зарегистрироваться';

return $form;
