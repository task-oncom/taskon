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
		<div class="error_box_text">
			<h3>Тестирование не завершено.</h3>
			<h6><?=Setting::getValue('not_attempt_message')?></h6>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="error_fedback">
			<?=CHtml::beginForm();?>
				<h6>Обратная связь</h6>
				<?=CHtml::textArea('message', '');?>
				<?=CHtml::ajaxSubmitButton(
					'Отправить', 
					Yii::app()->createUrl('/testings/testingTest/sendNotAttempt', array('id' => $model->id)), 
					array(
						'dataType' => 'json',
						'success' => 'js:function(data){
		                    if(data.success)
		                    {
		                    	console.log("+");
		                    	$(".error_fedback").html("<div class=\"row\"><div class=\"col-sm-8\" style=\"margin:0 auto;float:none;\"><div class=\"gray_box\"><p>" + data.message + "</p></div></div></div>");
		                    }
		                }'
					),
					array(
						'class' => 'green_button f_r'
					)
				);?>
				<div class="clear"></div>
			<?=CHtml::endForm();?>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="list_back">
			<a href="/testings/testingTest/index">Вернуться к списку тестов <span>&uarr;</span></a>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="read_box_wr">
			<div class="read_box">
				<p>Тестирование не завершено.</p>
			</div>
		</div>
	</div>
</div>

<br>