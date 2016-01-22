<?php
$this->page_title = $model->test->session->name;

$this->crumbs = array(
	'Тестирование' => array('/testings/testingTest/index'),
	$model->test->name => false,
);
?>

<?php if (Yii::app()->user->hasFlash('flash')) : ?>
	<div class="message"><span>
		<?php echo Yii::app()->user->getFlash('flash'); ?>
	</span></div>
<?php endif; ?>


<div class="row">
	<div class="col-sm-12">
		<div class="not_passed_text">
			<?=Setting::getValue('text_pass_not_time');?>
		</div>
	</div>
</div>



<div class="test_parameters not_passed_test">
	<div class="row">
		<div class="col-sm-6">
			<h6>Тест пройден на:</h6>
			<h5><?php echo $model->percent; ?>%</h5>
		</div>
		<div class="col-sm-6">
			<h6>Прохождение теста у Вас заняло:</h6>
			<?php 
			$minutesText = TestingPassing::declOfNum(floor($model->time / 60), array(' минута ', ' минуты ', ' минут '));
			$secondsText = TestingPassing::declOfNum($model->time % 60, array(' секунда', ' секунды', ' секунд'));
			?>
			<h5><?=$minutesText?><?=$secondsText?></h5>
		</div>
	</div>
	<br>
</div>

<?php if($model->gammas && !$model->test->mix) : ?>
	<div class="row">
		<div class="col-sm-12">
			<div class="statistics_test_wr">
				<ul class="statistics_test">
					<?php foreach ($model->gammas as $gamma) : ?>
						<li><span><?php echo $model->gammaPercent($gamma->id); ?>%</span><?php echo $gamma->name; ?> (<?php echo TestingGamma::$type_list[$gamma->type]; ?>)</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
<?php endif; ?>

<div class="row">
	<div class="col-sm-12">
		<div class="list_back">
			<a href="/testings/testingTest/index">Вернуться к списку тестов <span>↑</span></a>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="green_wr not_passed_test_progress">
			<div class="green_box">
				<div class="progress_ins">
					<span class="col_text">Вопрос <strong class="green_text"><?=$model->test->questions?></strong> из <strong class="green_text"><?=$model->test->questions?></strong></span>
					<div class="progress_wr">
						<div class="progress">
							<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								<span class="sr-only">100% Complete (success)</span>
							</div>
						</div>
					</div>
					<div class="progress_done">
						Выполнено 100%
					</div>
				</div>
				<div class="time_progress">
					<p>Затраченное время:</p>
					<span><?=date("H:i:s", mktime(0, 0, $model->time));?></span>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
</div>
<br>