<?php

$this->tabs = array(
    //'управление'    => $this->createUrl('manage'),
    'редактировать' => $this->createUrl('update', array('passing' => $model->passing_id))
);

$this->widget('DetailView', array(
	'data' => $model,
	'attributes' => array(
		/*array(
			'name' => 'passing_id',
			'value' => $model->passing->name,
		),*/
		array('name' => 'description'),
		array(
			'name' => 'is_expert_agreed',
			'value' => TestingMistake::$state_list[$model->is_expert_agreed],
		),
		array('name' => 'create_date'),
	),
));

if ($model->files) {
	$this->widget('fileManager.portlets.FileList', array(
		'model' => $model,
		'tag' => 'files',
		'tagName' => 'div',
		'htmlOptions' => array(
			'class' => 'file-list',
			'style' => 'margin: 20px 10px 0 10px;'
		),
	));
}