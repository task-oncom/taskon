<?php
$this->tabs = array(
    "Группы пользователей" => $this->createUrl("manage")
);

if ($dp instanceof CActiveDataProvider)
{
    $key = $dp->keyAttribute;
}
elseif ($dp instanceof CArrayDataProvider)
{
    $key = $dp->keyField;
}

Yii::import('zii.widgets.grid.CCheckboxColumn');
$this->widget('AdminGrid', array(
    'id'           => 'admin-grid',
    'dataProvider' => $dp,
    'columns'      => array(
        array(
            'name'  => $key,
            'header'=> 'Название',
            'type'  => 'raw'
        ), array(
            'header'     => 'Редактирование',
            'class'      => 'AccessColumn',
            'name'       => 'edit_op',
            'role'       => $role,
            'action'     => 'Admin',
            'htmlOptions'=> array(
                'class' => 'edit-op'
            ),
        ),
    ),
));
?>
