<?php

	$this->page_title = $model->test->session->name;

	$this->crumbs = array(
		'Начало тестирования' => array('/testings/testingTest/index'),
		$model->test->name => false,
	);
?>

<style type="text/css">
	[data-toggle=buttons] .btn input {
		position: absolute;
	    clip: rect(0,0,0,0);
	    pointer-events: none;
	}
	.gray_wr {
	    padding: 5px 14px;
	    margin-bottom: 6px;
	}
</style>

<?php if (Yii::app()->user->hasFlash('flash')) : ?>
	<div class="message"><span>
		<?php echo Yii::app()->user->getFlash('flash'); ?>
	</span></div>
<?php endif; ?>

<script type="text/javascript">
	jQuery(function(){
		var mg_need = <?php echo $model->test->mg_count; ?>;
		var te_need = <?php echo $model->test->te_count; ?>;

		var submit_button = jQuery('#submit_button');

		var check_number = function()
		{
			var mg = jQuery("input:checkbox[id^=mg]:checked").length;
			var te = jQuery("input:checkbox[id^=te]:checked").length;

			if (mg_need <= mg && te_need <= te) {
				submit_button.removeAttr('disabled');
			} else {
				submit_button.attr('disabled','disabled');
			}
		}
		check_number();
		$('input:checkbox').change(check_number);
	});
</script>


<br>

<form method="post" id="choose-gammas-form">
	<h2>Выберите гаммы продукции, из которых будет формироваться ваш тест</h2>

	<br />

	<?php if($model->test->gammasMG) { ?>
		<h6>Выберите несколько гамм продукции (не менее <?php echo $model->test->mg_count; ?>) из строительной гаммы:</h6>

		<div data-toggle="buttons" class="btn-group">
			<?=NHtml::checkBoxList('mg', $mg_gammas, CHtml::listData($model->test->gammasMG, 'id', 'name'), array(
				'template' => '<div class="gray_wr checkbox_button_wr_2">{beginLabel}{input}{labelTitle}{endLabel}</div>',
				'labelOptions' => array(
					'class' => 'btn btn-primary'
				),
				'autocomplete' => 'off',
				'separator' => false
			));?>
		</div>

		<br />
		<br />
	<?php } ?>

	<?php if($model->test->gammasTE) { ?>
		<h6>Выберите несколько гамм продукции (не менее <?php echo $model->test->te_count; ?>) из промышленной гаммы:</h6>

		<div data-toggle="buttons" class="btn-group">
			<?=NHtml::checkBoxList('te', $te_gammas, CHtml::listData($model->test->gammasTE, 'id', 'name'), array(
				'template' => '<div class="gray_wr checkbox_button_wr_2">{beginLabel}{input}{labelTitle}{endLabel}</div>',
				'labelOptions' => array(
					'class' => 'btn btn-primary'
				),
				'autocomplete' => 'off',
				'separator' => false
			));?>
		</div>

		<br />
		<br />
	<? } ?>

	<div class="row">
		<div class="col-sm-12">
			<button type="submit" id="submit_button" class="green_button">Подтвердить</button>
		</div>
	</div>
</form>

<br style="clear: both;" />
