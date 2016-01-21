<?php

$this->tabs = array(
    //'управление'    => $this->createUrl('manage'),
    'редактировать' => $this->createUrl('update', array('id' => $model->id))
);

$this->widget('DetailView', array(
	'data' => $model,
	'attributes' => array(
		array('name' => 'server'),
		array('name' => 'port'),
		array('name' => 'email'),
		array('name' => 'username'),
		array('name' => 'password'),
		array('name' => 'create_date'),
	),
));
