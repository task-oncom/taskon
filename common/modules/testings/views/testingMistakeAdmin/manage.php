<?php
/*
$this->tabs = array(
    'добавить' => $this->createUrl('create')
);
*/
$this->widget('AdminGrid', array(
	'id' => 'testing-mistake-grid',
	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		array('name' => 'passing_id'),
		array('name' => 'description'),
		array(
			'name' => 'is_expert_agreed',
			'value' => 'TestingMistake::$state_list[$data->is_expert_agreed]',
		),
		array('name' => 'create_date'),
		array(
			'class' => 'CButtonColumn',
		),
	),
));

