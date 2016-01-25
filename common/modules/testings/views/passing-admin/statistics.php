<?php

use yii\helpers\Html;
use \common\components\zii\AdminGrid;

use common\modules\testings\models\Test;
use common\modules\testings\models\Passing;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
	.date_to, .date-to {
		display: none;
	}
	#organisations-field {
		margin-left: 10px;
	}
	.date-block {
		margin: 15px 0 15px 0;
	}
	.date-fields {
		margin-left: 28px;
	}
</style>
<script>
	$(document).ready(function() {
		// $("#organisations-field").bind('change', function () {
		// 	$("#company_name").val($(this).val());
		// 	$("#company_name").change();
		// });

		// $("#date-from").bind('change', function () {
		// 	pass_date = $(this).val();
		// 	date_to   = $("#date-to").val();
			
		// 	$("#pass_date").find("input").val(pass_date);
		// 	$("#date_to").val(date_to);
			
		// 	$("#date_to").change();
		// });

		// $("#date-to").bind('change', function () {
		// 	pass_date = $("#date-from").val();
		// 	date_to   = $(this).val();
			
		// 	$("#pass_date").find("input").val(pass_date);
		// 	$("#date_to").val(date_to);
			
		// 	$("#date_to").change();
		// });

		$("body").bind('mousemove', function() {
			p = $("#testing-passing-grid").attr('passed');
			n = $("#testing-passing-grid").attr('notpassed');
			np = $("#testing-passing-grid").attr('notpassedyet');
			npe =$("#testing-passing-grid").attr('notpasssederror');
			
			$("#p-place").html(p);
			$("#n-place").html(n);
			$("#np-place").html(np);
			$("#npe-place").html(npe);
		});
	});
</script>

<div class="statistics-block">
	<div class="statistics">
		Сдано <span id="p-place"></span><br />
		Не сдано <span id="n-place"></span><br />
		Не сдавало <span id="np-place"></span><br />
		Ошибка <span id="npe-place"></span><br />
	</div>
</div>

<?php echo AdminGrid::widget([
    'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'id' => 'testing-passing-grid',
	'options' => array(
		'passed' => $searchModel->getCountPassed(),
		'notpassed' => $searchModel->getCountNotPassed(),
		'notpassedyet' => $searchModel->getCountNotPassedYet(),
		'notpasssederror' => $searchModel->getCountPassedError()
	),
    'columns' => [
       	// ['class' => 'yii\grid\SerialColumn'],

    	[
            'attribute' => 'user_id',
            'format' => 'html',
            'value' => function($model)
            {
            	if($model->user)
            	{
            		return Html::a($model->user->fio, ["/testings/user-admin/view", "id" => $model->user->id]);
            	}
            	else
            	{
            		return "Пользователь удален";
            	}
            }
        ],
        [
            'attribute' => 'filter_user_email',
            'format' => 'html',
            'value' => function($model)
            {
            	return Html::a($model->user->email, "mailto:" . $model->user->email);
            }
        ],
        [
            'attribute' => 'filter_user_company_name',
            'format' => 'html',
            'value' => function($model)
            {
            	return $model->user->company_name;
            }
        ],
        [
            'attribute' => 'test_id',
            'format' => 'html',
            'filter' => Test::getTestsList($session->id),
            'value' => function($model)
            {
            	return Html::a($model->test->name, ["/testings/test-admin/view","id" => $model->test_id]);
            }
        ],
        [
            'attribute' => 'is_passed',
            'format' => 'html',
            'filter' => Passing::$state_list,
            'value' => function($model)
            {
            	return Html::tag("span", Passing::$state_list[$model->status], ["class" => "STATE" . $model->status]);
            }
        ],
		[
			'attribute' => 'pass_date',
			'filterOptions' => [
				'id' => 'pass_date'
			],
		],
		[
            'header' => 'Ответственный менеджер',
            'format' => 'html',
            'value' => function($model)
            {
            	if($model->user && $model->user->manager)
            	{
            		return Html::a($model->user->manager->name, ["/users/user-admin/view", "id" => $model->user->manager->id]);
            	}
            	else
            	{
            		return "Пользователь удалён";
            	}
            }
        ],
		[
            'header' => 'Загрузить сообщение об ошибке',
            'format' => 'html',
            'filter' => false,
            'value' => function($model)
            {
            	if($model->is_passed !== null)
            	{
            		if($model->mistake)
            		{
            			$text = "Сведения " . $model->mistake->create_date;
            		}
            		else
            		{
            			$text = "Загрузить";
            		}
            		return Html::a($text, ["/testings/passing-admin/mistake", "id" => $model->id]);
            	}
            	else
            	{
            		return "";
            	}
            }
        ],
  //       array(
		// 	'header'=> 'date_to',
		// 	'value' => '',
		// 	'type' => 'html',
		// 	'htmlOptions' => array('class' => 'date_to'),
		// 	'headerHtmlOptions' => array('class' => 'date_to'),
		// 	'filterHtmlOptions' => array('class' => 'date-to'),
		// 	'filter' => CHtml::textField('date_to', ''),
		// ),
        [
            'class' => 'common\components\ColorActionColumn',
	        'template' => '{view} {delete}',
        ],
    ],
]); ?>