<?php

$test_id = null;

if (Yii::app()->request->getQuery('test')) {
	$test = TestingTest::model()->findByPk(Yii::app()->request->getQuery('test'));
	if ($test) {
		$test_id = $test->id;

		$this->crumbs = array(
			'Список сессий' => array('/testings/testingSessionAdmin/manage'),
			'Сессия "'.$test->session->name.'"' => array('/testings/testingSessionAdmin/view','id'=>$test->session->id),
			'Список тестов' => array('/testings/testingTestAdmin/manage','session'=>$test->session->id),
			$test->name => array('/testings/testingTestAdmin/view','id'=>$test->id),
			'Список гамм'
		);
	}

	$this->tabs = array(
		'импорт вопросов из CSV-файла' => $this->createUrl('/testings/testingTestAdmin/importTests',array('id'=>$test->id)),
	);

}

$this->tabs = CMap::mergeArray($this->tabs, array(
    'добавить' => $this->createUrl('create',array('test'=>$test_id))
));


$this->widget('AdminGrid', array(
	'id' => 'testing-gamma-grid',
	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		array(
			'name' => 'name',
			'value' => '$data->name',
		),
		array(
			'header' => 'Заполненность',
			'value' => 'CHtml::tag("span", array("class"=>"fill_mark", "style"=>"font-weight: bold;"), ($data->type == TestingGamma::MG) ? $data->questionCount('.$test_id.')."/".(TestingTest::model()->findByPk('.$test_id.')->questions * TestingTest::model()->findByPk('.$test_id.')->mg_percent / (TestingTest::model()->findByPk('.$test_id.')->mg_percent + TestingTest::model()->findByPk('.$test_id.')->te_percent) / TestingTest::model()->findByPk('.$test_id.')->mg_count) : $data->questionCount('.$test_id.')."/".(TestingTest::model()->findByPk('.$test_id.')->questions * TestingTest::model()->findByPk('.$test_id.')->te_percent / (TestingTest::model()->findByPk('.$test_id.')->mg_percent + TestingTest::model()->findByPk('.$test_id.')->te_percent) / TestingTest::model()->findByPk('.$test_id.')->te_count))',
			'visible' => $test_id,
			'type' => 'html',
		),
		array(
			'name' => 'type',
			'value' => 'TestingGamma::$type_list[$data->type]',
			'filter' => false,
		),
		//array('name' => 'create_date'),
		array(
			'class' => 'CButtonColumn',
			'template' => '{view}{update}',
		),
	),
));

?>

<script type="text/javascript">
	jQuery(function(){
		jQuery(".fill_mark").each(function(){
			var $this = $(this);
			var str = $this.html();
			var arr = str.split('/');
			console.log(arr);
			if (parseInt(arr[0]) >= parseInt(arr[1])) {
				$this.css('color','green');
			} else {
				$this.css('color','red');
			}
		});
	});
</script>