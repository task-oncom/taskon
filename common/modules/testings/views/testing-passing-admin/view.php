<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use common\modules\testings\models\TestingQuestion;

/* @var $this yii\web\View */

$this->title = $model->text;

?>

<div class="faq-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        	[
				'attribute' => 'user_id',
				'value' => function($model)
				{
					if($model->user)
					{
						return Html::a($model->user->fio, ["/testings/testing-user-admin/view", "id" => $model->user->id]);
					}
					else
					{
						return "Пользователь удалён";
					}
				},
				'format' => 'html',
			],
			[
				'attribute' => 'session_id',
				'value' => function($model)
				{
					return Html::a($model->test->session->name, ["/testings/testing-session-admin/view", "id" => $model->test->session_id]);
				},
				'format' => 'html',
			],
			[
				'attribute' => 'test_id',
				'value' => function($model)
				{
					return Html::a($model->test->name, ["/testings/testing-test-admin/view", "id" => $model->test_id]);
				},
				'format' => 'html',
			],
			[
				'attribute' => 'is_passed',
	            'format' => 'raw',
	            'value' =>   function($model)
	            {
	            	return TestingPassing::$state_list[$model->is_passed] . " ({$model->CountPassedQuestions} - {$model->percent_rights}%)";
	            },
			],
			'pass_date',
        ],
    ]) ?>

</div>

























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

