<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'session_id'); ?>
		<?php echo $form->textField($model,'session_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'minutes'); ?>
		<?php echo $form->textField($model,'minutes'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'questions'); ?>
		<?php echo $form->textField($model,'questions'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pass_percent'); ?>
		<?php echo $form->textField($model,'pass_percent'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'mg_percent'); ?>
		<?php echo $form->textField($model,'mg_percent'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'te_percent'); ?>
		<?php echo $form->textField($model,'te_percent'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'mg_count'); ?>
		<?php echo $form->textField($model,'mg_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'te_count'); ?>
		<?php echo $form->textField($model,'te_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_date'); ?>
		<?php echo $form->textField($model,'create_date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->