<?php

$this->crumbs = false;

?>

<br>

<?php
$this->widget('application.components.zii.GridView', array(
	'dataProvider' => $present,
    'emptyText'        => 'Тестов в этой категории нет',
    // 'enablePagination' => false,
    'template' => '{items} <div class="row"><div class="col-sm-12"><div class="pages">{pager}</div></div></div>',
    'pagerCssClass' => '',
    'pager' => array(
    	'header'         => '',
        'firstPageLabel' => 'Первая',
        'cssFile' => false,
        'prevPageLabel'  => '',
        'nextPageLabel'  => '',
        'lastPageLabel'  => 'Последняя',
    ),
    'summaryText'      => false,
    'ajaxUpdate'       => false,
	'columns' => array(
		array('name' => 'test.session.name'),
		array(
			'name' => 'test.name',
			'value' => '"<b>".$data->test->name."</b>"',
			'type' => 'html',
		),
		array(
			// 'name' => 'is_passed',
			'header' => 'Состояние',
			'value' => 'TestingPassing::$state_list[$data->status]',
		),
		array(
			'header' => 'Сдать до',
			'value' => '($data->status === null OR $data->status==TestingPassing::NOT_STARTED) ? $data->test->session->end_date : ""',
		),
		array(
			'header' => 'Информация',
			'value' => function($data) {
				if($data->status === null OR $data->status==TestingPassing::NOT_STARTED)
				{
					return CHtml::link("Пройти тестирование >>", array("/testings/testingTest/info", "id"=>$data->id));
				}
				elseif($data->status==TestingPassing::STARTED AND $data->attempt < $data->test->attempt AND strtotime($data->pass_date_start) + ($data->test->minutes * 60) >= time())
				{
					return CHtml::link("Пройти тестирование >>", array("/testings/testingTest/pass", "id"=>$data->id));
				}
				elseif($data->status == TestingPassing::PASSED || (int)$data->status === (int)TestingPassing::FAILED)
				{
					return CHtml::link("Статистика", array("/testings/testingTest/statistic", "id"=>$data->id));
				}
			},
			'type' => 'html',
		),

	),
	'itemsCssClass' => 'sale_table',
));
