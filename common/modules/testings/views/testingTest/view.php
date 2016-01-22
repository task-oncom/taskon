<?php 
$this->widget('zii.widgets.CDetailView', array(
	'data' => $model,
	'attributes' => array(
		'id',
		'session_id',
		'name',
		'minutes',
		'questions',
		'pass_percent',
		'mg_percent',
		'te_percent',
		'mg_count',
		'te_count',
		'create_date',
	),
)); 
?>

