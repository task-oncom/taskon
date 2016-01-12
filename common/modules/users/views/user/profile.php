<?php
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('Главная', '/'),
    'separator' => ' / ',
    'links' => array(
		'Кабинет' => '/cabinet',
		'Личные данные',
    ),
    'htmlOptions' => array(
        'class' => 'way'
    )
));
?>
<h6 class="f_lift">Личные данные</h6>
<div class="clear"></div>
<div class="line_color_litle"></div>
<br>
<p>Здесь Вы можете изменить свои личные данные. Если не хотите менять пароль, не заполняйте последние два поля.</p>
<h2 class="f_left">Изменение личных данных</h2>
<a class="f_right registr_a" href="/cabinet">Список заказов</a>
<div class="clear"></div>


<div id="catal0g">
    <?php if ($saved) { ?>
    <p style="padding-bottom: 100px;">Данные были успешно изменены. Хотите перейти в <a href="/catalog" title="Каталог продукции">каталог продукции</a>?</p>
    <?php } ?>



<?php if ($saved) echo '<!--'; ?>

<div class="basket-step3" style="display:block;">
<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>true,
// 	'clientOptions' => array(
// 		'validateOnSubmit' => true,
// 		'validateOnChange' => false,
// 	),
	'htmlOptions' => array(
		'class' => 'registr',
		'style' => 'float: left;',
	),
)); ?>




    <dl class="label"><dd><?php echo $form->label($model, 'fio'); ?></dd></dl>
    <dl class="text"><dd><?php echo $form->textField($model, 'fio')?></dd></dl><dl class="message" id="phone"><dd></dd></dl>
    <dl class="message"><dd></dd><dd class="label_read"><?php echo $form->error($model,'fio',array('class'=>'label_read')); ?></dd></dl>


    <dl class="label"><dd><?php echo $form->label($model, 'email'); ?></dd></dl>
    <dl class="text"><dd><?php echo $form->textField($model, 'email')?></dd></dl><dl class="message" id="phone"><dd></dd></dl>
    <dl class="message"><dd></dd><dd class="label_read"><?php echo $form->error($model,'email',array('class'=>'label_read')); ?></dd></dl>



    <dl class="label"><dd><?php echo $form->label($model, 'phone'); ?></dd></dl>
    <dl class="text"><dd><?php echo $form->textField($model, 'phone')?></dd></dl><dl class="message" id="phone"><dd></dd></dl>
    <dl class="message"><dd></dd><dd class="label_read"><?php echo $form->error($model,'phone',array('class'=>'label_read')); ?></dd></dl>



    <dl class="label"><dd><?php echo $form->label($model, 'password'); ?></dd></dl>
    <dl class="text"><dd><?php echo $form->passwordField($model, 'password')?></dd></dl><dl class="message" id="phone"><dd></dd></dl>
    <dl class="message"><dd></dd><dd class="label_read"><?php echo $form->error($model,'password',array('class'=>'label_read')); ?></dd></dl>


    <dl class="label"><dd><?php echo $form->label($model, 'password_c'); ?></dd></dl>
    <dl class="text"><dd><?php echo $form->passwordField($model, 'password_c')?></dd></dl><dl class="message" id="phone"><dd></dd></dl>
    <dl class="message"><dd></dd><dd class="label_read"><?php echo $form->error($model,'password_c',array('class'=>'label_read')); ?></dd></dl>

    <br>
    <dl><dd><?=CHtml::submitButton('Сохранить данные', array('id' => "submit")); ?></dd></dl>
    <div class="clear"></div>


<?php $this->endWidget(); ?>
</div>
<?php if ($saved) echo '-->'; ?>

<script>
$('#user-form').addClass('registr');
</script>

</div>