<?php

$this->page_title = 'Список прохождений';

?>


<style type="text/css">
	.STATE1 {
		font-weight: bold;
		color: #449944;
	}
	.STATE0, .STATE2 {
		font-weight: bold;
		color: #ee4444;
	}
	.STATE, .STATE3 {
		color: #999;
	}
</style>

<?php

echo CHtml::dropDownList('sessions_list', $session ? $session->id : '', CHtml::listData($sessions, 'id', 'name'), array('onchange' => 'js:document.location.href = "/testings/TestingPassingTKI/manage/session/"+this.value'));
echo CHtml::hiddenField('TestingPassing[session]', $session ? $session->id : '');

if ($session)
{
$this->widget('AdminGrid', array(
	'id' => 'testing-passing-grid',

	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		array(
			'name' => 'user_id',
			'value' => '($data->user)?CHtml::link($data->user->fio,array("/testings/testingUserAdmin/view","id"=>$data->user->id)):"Пользователь удалён"',
			'type' => 'html',
			'filter' => CHtml::textField('fio', ''),
		),
		array(
			'name' => 'user.email',
			'value' => 'CHtml::link($data->user->email, "mailto:".$data->user->email)',
			'type' => 'html',
			'filter' => CHtml::textField('email', ''),
		),
		array(
			'header' => 'Наименование компании',
			'value' => '$data->user->company_name',
			'filter' => CHtml::textField('company_name', ''),
		),
		array(
			'name' => 'test_id',
			'value' => 'CHtml::link($data->test->name,array("/testings/testingTestAdmin/view","id"=>$data->test_id))',
			'type' => 'html',
			'filter' => TestingTest::getTestsList($session->id),
		),
		array(
			'name' => 'is_passed',
            'value' => function($data, $row) {
                $text = '';
                return CHtml::tag("span",array("class"=>"STATE".$data->status),
                        TestingPassing::$state_list[$data->status].$text
                        );
            },
			'type' => 'html',
			'filter' => TestingPassing::$state_list,
		),
		array(
			'name' => 'pass_date',
		),
		array(
			'header' => 'Ответственный менеджер',
			'value' => '($data->user && $data->user->manager)?CHtml::link($data->user->manager->name,array("/users/userAdmin/view","id"=>$data->user->manager->id)):"Пользователь удалён"',
			'type' => 'html',
			//'filter' => false,
		),
		array(
			'header' => 'Загрузить сообщение об ошибке',
			'value' => '(!($data->is_passed === null)) ? (CHtml::link(($data->mistake)?"Сведения ".$data->mistake->create_date:"Загрузить",array("/testings/testingPassingAdmin/mistake","id"=>$data->id))) : ""',
			'type' => 'html',
			'filter' => false,
		),

		array(
			'class' => 'CButtonColumn',
			'template' => '{view}{delete}',
		),

	),
));
}
