<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use \common\components\zii\AdminGrid;

use common\modules\testings\models\Passing;

/* @var $this yii\web\View */

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

<div class="faq-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        	[
				'attribute' => 'user_id',
				'value' => ($model->user) ? Html::a($model->user->fio, ["/testings/user-admin/view", "id" => $model->user->id]) : "Пользователь удалён",
				'format' => 'html',
			],
			[
				'attribute' => 'session_id',
				'value' => Html::a($model->test->session->name, ["/testings/session-admin/view", "id" => $model->test->session_id]),
				'format' => 'html',
			],
			[
				'attribute' => 'test_id',
				'value' => Html::a($model->test->name, ["/testings/test-admin/view", "id" => $model->test_id]),
				'format' => 'html',
			],
			[
				'attribute' => 'is_passed',
	            'format' => 'raw',
	            'value' => Passing::$state_list[$model->is_passed] . " ({$model->CountPassedQuestions} - {$model->percent_rights}%)",
			],
			'pass_date',
        ],
    ]) ?>

</div>


<br /><br />
<h1>Подробная статистика по ответам</h1>
<h3>(Всего времени затрачено: <?php echo $time; ?>)</h3>


<?php echo AdminGrid::widget([
    'dataProvider' => $dataProvider,
    // 'emptyText'=>'Подробная статистика появится тут после того, как пользователь начнёт тестирование',
	'filterModel' => $searchModel,
    'columns' => [
       	// ['class' => 'yii\grid\SerialColumn'],

    	[
            'attribute' => 'question_id',
            'value' => function($model)
            {
            	return $model->question->text;
            }
        ],
        [
            'header' => 'Правильный ответ',
            'value' => function($model)
            {
            	return $model->question->rightAnswer;
            }
        ],
        'user_answer',
        [
            'header' => 'Ответ',
            'value' => function($model)
            {
            	return Html::tag("span", Passing::$answer_list[$model->isRight], ["class"=>"STATE".$model->isRight]) . " " . Html::a("Изменить на обратное", ["/testings/passing-admin/change-answer-status", "qp_id" => $model->id]);
            },
            'format' => 'html'
        ],
        'answer_time',
    ],
]); ?>


