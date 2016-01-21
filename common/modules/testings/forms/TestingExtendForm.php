<?php
$users = TestingUser::model()->findAll();
$arr = '';
foreach ($users as $user)
{
    $arr .= '{label:"'.$user->fio.'", value:"'.$user->id.'"},';
}

return array(
    'activeForm' => array(
        'id' => 'testing-passing-form',
    ),
    'elements' => array(
        'user_id' => array(
			'type' => 'dropdownlist',
			'items' => CHtml::listData(TestingUser::model()->findAll(),'id','fio'),
		),
        '
        <label>Поиск пользователя</label>
        <input type="text" class="text" id="autocomplete">
        <script>
            $(window).load(function(){
            availableTags = ['.$arr.'];
    $( "#autocomplete" ).autocomplete({
        source: availableTags,
        select: function(event, ui) { $("#TestingPassing_user_id [value="+ui.item.value+"]").attr("selected", "selected"); },
    });    });
</script>',
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
