<?php

$this->widget('DetailView', array(
	'data' => $model,
	'attributes' => array(
		array(
			'name' => 'user_id',
			'value' => ($model->user)?$model->user->fio:"Пользователь удалён",
			'type' => 'html',
		),
		array(
			'name' => 'session_id',
			'value' => $model->test->session->name,
			'type' => 'html',
		),
		array(
			'name' => 'test_id',
			'value' => $model->test->name,
			'type' => 'html',
		),
		array(
			'name' => 'is_passed',
            'type' => 'raw',
            'value' =>   TestingPassing::$state_list[$model->is_passed]
                        ." ({$model->CountPassedQuestions} - {$model->percent_rights}%)",

            /*
			'value' => function() {
                return $text;
            }*/
		),
		array('name' => 'pass_date'),
		//array('name' => 'create_date'),
	),
));
?>


<style type="text/css">
	.STATE1 {
		font-weight: bold;
		color: #449944;
	}
	.STATE {
		font-weight: bold;
		color: #ee4444;
	}
</style>

<br /><br />
<h1>Подробная статистика по ответам</h1>
<h3>(Всего времени затрачено: <?php echo $time; ?>)</h3>

<?php
	$this->widget('AdminGrid', array(
		'id' => 'testing-question-passing-grid',
		'dataProvider' => $questions->search($model->id),
		'filter' => $questions,
		'columns' => array(
			array(
				'name' => 'question_id',
				'value' => '$data->question->text',
				'filter' => false,
			),
			array(
				'header' => 'Правильный ответ',
				'value' => '$data->question->rightAnswer',
				'filter' => false,
			),
			array(
				'name' => 'user_answer',
				'filter' => false,
			),
			array(
				'header' => 'Ответ',
				'value' => 'CHtml::tag("span",array("class"=>"STATE".$data->isRight),TestingPassing::$answer_list[$data->isRight])." ".'
					.'CHtml::link("Изменить на обратное",array("/testings/testingPassingAdmin/changeAnswerStatus","qp_id"=>$data->id))',
				'filter' => false,
				'type' => 'html',
			),
			array(
				'name' => 'answer_time',
				'filter' => false,
			),
		),
		'emptyText'=>'Подробная статистика появится тут после того, как пользователь начнёт тестирование',
	));

