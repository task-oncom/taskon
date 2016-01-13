<?php 
$this->page_title = 'Задачи'; 

$this->tabs = array(
    "Добавить задачу" => $this->createUrl("create")
);

$this->widget('AdminGrid', array(
	'id' => 'task-grid',
	'dataProvider' => $model->search(AuthItem::TYPE_TASK),
	'filter'       => $model,
	'columns' => array(
        'name',
        'description',
        array('name' => 'allow_for_all', 'value' => '$data->allow_for_all ? "Да" : "Нет"'),
		array(
			'class' => 'CButtonColumn',
            'buttons' => array(
                'update' => array(
                    'url' => 'Yii::app()->controller->createUrl("update", array("id" => $data->primaryKey))'
                ),
                'view' => array(
                    'url' => 'Yii::app()->controller->createUrl("view", array("id" => $data->primaryKey))'
                ),
                'delete' => array(
                    'url' => 'Yii::app()->controller->createUrl("delete", array("id" => $data->primaryKey))'
                )
            )
		),
	),
)); 

