<?php

Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl() . '/js/rbac.js');

function rolesSelect($user)
{
	$roles = AuthItem::model()->findAllByAttributes(array('type' => AuthItem::TYPE_ROLE));

	$html = "<select class='assign_select' user_id='{$user->id}'>";

	foreach ($roles as $role) 
	{	
		$selected = "";

        if (isset($user->role->name) && $user->role->name == $role->name)
        {
            $selected = "selected";
        }

		$html.= "<option value='{$role->name}' {$selected}>{$role->description}</option>";
	}	

	$html.= "</select>";

	return $html;
}

$this->widget('AdminGrid', array(
	'id' => 'assigment-grid',
	'dataProvider' => $model->search(),
	'filter'   => $model,
	'template' => '{summary}<br/>{pager}<br/>{items}<br/>{pager}',
	'columns'  => array(
		'fio',
		'email',
		array('name' => 'role', 'value' => 'rolesSelect($data);', 'type' => 'raw','filter'=>false),
		array(
			'class' => 'CButtonColumn',
			'template' => ''
		),
	),
));

?>
