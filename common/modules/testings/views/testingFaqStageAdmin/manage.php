<?php

$this->crumbs = array(
);

$this->tabs = array(
    'управление справками' => $this->createUrl('/testings/testingFaqAdmin/manage'),
    'добавить' => $this->createUrl('create', array('faq' => Yii::app()->request->getQuery('faq')))
);


$this->widget('AdminGrid', array(
	'id' => 'testing-answer-grid',
	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		'title',
		array(
			'class' => 'CButtonColumn',
		),
	),
));

