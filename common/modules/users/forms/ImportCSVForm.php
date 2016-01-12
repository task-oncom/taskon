<?php

$roles = AuthItem::model()->findAllByAttributes(
	array('type' => AuthItem::TYPE_ROLE),
	"name != '" . AuthItem::ROLE_GUEST . "'"
);

return array(
	'activeForm' => array(
		'id'    => 'import-csv-form',
		'class' => 'CActiveForm',
		'htmlOptions' => array('enctype' => 'multipart/form-data')
	),
	'elements' => array(
        'send_email' => array('type'=>'checkbox'),
        'role' => array(
        	'type'  => 'dropdownlist',
        	'items' => CHtml::listData($roles, 'name', 'description')
    	),
    	'csv_file' => array(
    		'type' => 'file'
    	)
	),
	'buttons' => array(
		'submit' => array(
			'type'  => 'submit',
			'value' => 'Загрузить' 
		)
	)
);