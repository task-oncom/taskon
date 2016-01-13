<?php
$this->page_title = 'Добавление группы пользователя';

$this->tabs = array(
    "управление ролями" => $this->createUrl("create"),
);

echo $form;
?>