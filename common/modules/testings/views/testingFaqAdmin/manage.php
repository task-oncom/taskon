<?php

$this->crumbs = array(
);

$this->tabs = array(
    'добавить' => $this->createUrl('create')
);


$this->widget('AdminGrid', array(
	'id' => 'testing-answer-grid',
	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		'id',
		'title',
		'url',
		array(
			'header' => 'Управление пунктами',
			'type' => 'html',
			'value' => function($data) 
			{
				return CHtml::link('Пункты', array('/testings/testingFaqStageAdmin/manage', 'faq' => $data->id));
			}
		),
		array(
			'class' => 'CButtonColumn',
		),
	),
));

