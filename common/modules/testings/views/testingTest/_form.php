<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'testing-test-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'session_id'); ?>
		<?php echo $form->textField($model,'session_id'); ?>
		<?php echo $form->error($model,'session_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'minutes'); ?>
		<?php echo $form->textField($model,'minutes'); ?>
		<?php echo $form->error($model,'minutes'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'questions'); ?>
		<?php echo $form->textField($model,'questions'); ?>
		<?php echo $form->error($model,'questions'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pass_percent'); ?>
		<?php echo $form->textField($model,'pass_percent'); ?>
		<?php echo $form->error($model,'pass_percent'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mg_percent'); ?>
		<?php echo $form->textField($model,'mg_percent'); ?>
		<?php echo $form->error($model,'mg_percent'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'te_percent'); ?>
		<?php echo $form->textField($model,'te_percent'); ?>
		<?php echo $form->error($model,'te_percent'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mg_count'); ?>
		<?php echo $form->textField($model,'mg_count'); ?>
		<?php echo $form->error($model,'mg_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'te_count'); ?>
		<?php echo $form->textField($model,'te_count'); ?>
		<?php echo $form->error($model,'te_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'create_date'); ?>
		<?php echo $form->textField($model,'create_date'); ?>
		<?php echo $form->error($model,'create_date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->