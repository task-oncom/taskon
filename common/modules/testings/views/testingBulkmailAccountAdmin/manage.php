<?php
/*
$this->tabs = array(
    'добавить' => $this->createUrl('create')
);
*/
$this->widget('AdminGrid', array(
	'id' => 'bilkmail-account-grid',
	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		array('name' => 'server'),
		array('name' => 'port'),
		array('name' => 'smtp_secure'),
		array('name' => 'email'),
		array('name' => 'username'),
		array('name' => 'password'),
		array('name' => 'create_date'),
		array(
			'class' => 'CButtonColumn',
		),
	),
));

