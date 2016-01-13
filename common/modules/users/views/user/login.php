<h6>Авторизация</h6>
<div class="line_color_litle"></div>

<div class="street">

<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableAjaxValidation'=>true,
	'action' => '/login',
 	'clientOptions' => array(
// 		'validateOnSubmit' => true,
// 		'validateOnChange' => false,
    'errorCssClass' => 'hjnn',
 	),
	'htmlOptions' => array(
		'class' => 'registr',
	),
)); ?>

    <dl class="label"><dd><?php echo $form->label($model, 'email'); ?></dd></dl>
    <dl class="text"><dd><?php echo $form->textField($model, 'email')?></dd></dl><dl class="message" id="phone"><dd></dd></dl>
    <dl class="message"><dd></dd><dd class="label_read"><?php echo $form->error($model,'email',array('class'=>'label_read')); ?></dd></dl>

    <dl class="label"><dd><?php echo $form->label($model, 'password', array('style' => 'color: #46494E;')); ?></dd></dl>
    <dl class="text"><dd><?php echo $form->passwordField($model, 'password')?></dd></dl><dl class="message" id="phone"><dd></dd></dl>
    <dl class="message"><dd></dd><dd class="label_read"><?php echo $form->error($model,'password',array('class'=>'label_read')); ?></dd></dl>

    <?=CHtml::submitButton('Вход', array('id' => "submit", 'class'=>'e_button_1')); ?>



<?php $this->endWidget(); ?>

<script>
$('#user-form').addClass('registr');
</script>

</div>