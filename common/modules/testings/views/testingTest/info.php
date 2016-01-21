<?php

$this->page_title = $model->test->session->name;

$this->crumbs = array(
	'Начало тестирования' => array('/testings/testingTest/index'),
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
		<div class="before_test_text">
			<h3>Вы собираетесь сдать экзамен:</h3>
			<h5>«<?=$model->test->name?>»</h5>
		</div>
	</div>
</div>

<div class="test_parameters">
	<div class="row">
		<div class="col-sm-6">
			<img src="/images/testing/test_parameters_icon_1.png" height="83" width="70" alt="">
			<h4><?=$model->test->minutes?> минут</h4>
		</div>
		<div class="col-sm-6">
			<img src="/images/testing/test_parameters_icon_2.png" height="83" width="62" alt="">
			<h4><?=$model->test->questions?> вопросов</h4>
		</div>
	</div>
	
	<br>

	<div class="row">
		<div class="col-sm-12">
			<ul class="ul_box">
				<li>После подтверждения ответа вы не сможете проверить или изменить его.</li>
				<li>Если время закончится до того, как вы дадите достаточное количество правильных ответов, тест не будет сдан.</li>
				<li>Если вы перезагрузите страницу, закроете вкладку браузера, выключите компьютер и т.д., то сможете продолжить прохождение теста с последнего места.</li>
			</ul>

			<br>

			<?=CHtml::link('Начать тест', array("/testings/testingTest/genPass", "id" => $model->id), array('class' => 'green_button_big'))?>
		</div>
	</div>

</div>


