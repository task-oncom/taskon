<?php
$crit = new CDbCriteria;
$crit->distinct = true;
$crit->select = "company_name";
$crit->order = "company_name";
$companies = TestingUser::model()->findAll($crit);

$list = '<dl class="dropdownlist">
<dd>
<label class="required" for="TestingPassing_company">Компания <span class="required">*</span></label>
<select id="TestingPassing_company" name="TestingPassing[company]" class="dropdownlist cmf-skinned-select">';

foreach ($companies as $comp)
{
    $list.= '<option value="'.htmlspecialchars($comp->company_name).'">'.$comp->company_name.'</option>';
}

$list.= '</select></dd></dl>';

return array(
    'activeForm' => array(
        'id' => 'testing-passing-form',
    ),
    'elements' => array(
        $list,
        'test_id' => array(
			'type' => 'dropdownlist',
			'items' => CHtml::listData(TestingTest::model()->findAll(),'id','name','session.name'),
		),
        'end_date' => array(
			'type' => 'application.extensions.CJuiDateTimePicker.CJuiDateTimePicker',
			'htmlOptions' => array('class'=>'text')
		),
    ),
    'buttons' => array(
        'submit' => array(
            'type'  => 'submit',
            'value' => 'Записать')
    )
);
