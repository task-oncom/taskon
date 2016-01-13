<?php
$this->page_title = 'Редактирование группы пользователей';

$this->tabs = array(
    "управление ролями" => $this->createUrl("manage")
);

echo $form;
?>