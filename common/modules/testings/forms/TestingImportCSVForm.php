<?php

return array(
	'activeForm' => array(
		'id'    => 'import-csv-form',
		'class' => 'CActiveForm',
		'htmlOptions' => array('enctype' => 'multipart/form-data')
	),
	'elements' => array(
    	'csv_file' => array(
    		'type' => 'file',
    	)
	),
	'buttons' => array(
		'submit' => array(
			'type'  => 'submit',
			'value' => 'Загрузить',
		)
	)
);