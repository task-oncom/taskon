<?php

$this->tabs = array(
    'управление' => $this->createUrl('manage'),
    'просмотр'   => $this->createUrl('view', array('id' => $session->id)),
    'продлить'   => $this->createUrl('extend', array('id' => $session->id))
);

$this->crumbs = array(
	'Список сессий' => array('/testings/testingSessionAdmin/manage'),
	'Сессия "'.$session->name.'"' => array('/testings/testingSessionAdmin/view','id'=>$session->id),
	'Редактирование',
);

echo $form;
