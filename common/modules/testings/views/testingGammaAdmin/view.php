<?php

$this->tabs = array(
    'управление'    => $this->createUrl('manage'),
    'редактировать' => $this->createUrl('update', array('id' => $model->id))
);

$this->widget('DetailView', array(
	'data' => $model,
	'attributes' => array(
		array('name' => 'name'),
		array('name' => 'type', 'value'=> TestingGamma::$type_list[$model->type]),
		array('name' => 'create_date'),
	),
));


