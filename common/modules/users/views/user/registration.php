<?php
Yii::app()->clientScript->registerCssFile('/js/highslide/highslide.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->getBaseUrl().'/js/jquery.maskedinput-1.3.min.js', CClientScript::POS_END);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('Главная', '/'),
    'separator' => ' / ',
    'links' => array(
        'Регистрация',
    ),
    'htmlOptions' => array(
        'class' => 'way'
    )
));
?>
<div class="clear"></div>


<div class="news_left">
    <h6>ПОИСК ПО ПРОИЗВОДИТЕЛЮ</h6>
    <div class="line_color_litle"></div>

    <?php $this->widget('application.modules.catalog.widgets.ProducersWidget'); ?>

    <?php $this->widget('application.modules.articles.widgets.LastArticles'); ?>

    <?=Setting::getValue('cooperation_block')?>

</div>

<div class="box_right">
    <h6 class="f_lift">Регистрация</h6>
    <div class="clear"></div>
    <div class="line_color_litle"></div>
    <br>
    <p>Регистрация дает множество преимуществ по сравнению с незарегистрированными пользователями. Например, Вы можете
        сохранять в личном кабинете понравившиеся товары, а после вернуться к их покупки.</p>
    <h2 class="f_left"><?=(empty($is_created) ? 'Заполните форму для регистрации' : 'Подтвердите регистрацию')?></h2>
    <a class="f_right registr_a" href="/login">Уже регистрировались?</a>
    <div class="clear"></div>
    <div class="line_bottom"></div>



<?php if(empty($is_created)): ?>


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
            ),
        )); ?>



        <dl class="label"><dd>*<?php echo $form->label($model, 'fio'); ?></dd></dl>
        <dl class="text"><dd><?php echo $form->textField($model, 'fio')?></dd></dl><dl class="message" id="phone"><dd></dd></dl>
        <dl class="message"><dd></dd><dd class="label_read"><?php echo $form->error($model,'fio',array('class'=>'label_read')); ?></dd></dl>


        <dl class="label"><dd>*<?php echo $form->label($model, 'email'); ?></dd></dl>
        <dl class="text"><dd><?php echo $form->textField($model, 'email')?></dd></dl><dl class="message" id="phone"><dd></dd></dl>
        <dl class="message"><dd></dd><dd class="label_read"><?php echo $form->error($model,'email',array('class'=>'label_read')); ?></dd></dl>

<!--        <br/><dl><dd>Пожалуйста, оставьте ваш контактный телефон для того, что бы наши менеджеры могли с вами связаться.</dd></dl><br/>-->


        <dl class="label"><dd><?php echo $form->label($model, 'phone'); ?></dd></dl>
        <dl class="text"><dd><?php echo $form->textField($model, 'phone')?></dd></dl><dl class="message" id="phone"><dd></dd></dl>
        <dl class="message"><dd></dd><dd class="label_read"><?php echo $form->error($model,'phone',array('class'=>'label_read')); ?></dd></dl>

<!--        <br/><dl><dd>Чтобы не изменять пароль, оставьте поля пароля пустыми.</dd></dl><br/>-->

        <dl class="label"><dd>*<?php echo $form->label($model, 'password'); ?></dd></dl>
        <dl class="text"><dd><?php echo $form->passwordField($model, 'password')?></dd></dl><dl class="message" id="phone"><dd></dd></dl>
        <dl class="message"><dd></dd><dd class="label_read"><?php echo $form->error($model,'password',array('class'=>'label_read')); ?></dd></dl>


        <dl class="label"><dd>*<?php echo $form->label($model, 'password_c'); ?></dd></dl>
        <dl class="text"><dd><?php echo $form->passwordField($model, 'password_c')?></dd></dl><dl class="message" id="phone"><dd></dd></dl>
        <dl class="message"><dd></dd><dd class="label_read"><?php echo $form->error($model,'password_c',array('class'=>'label_read')); ?></dd></dl>
<br>
        <div class="line_bottom"></div>
        <p class="etention"><span>*</span> Обязательные для заполнения поля</p>
        <?=CHtml::submitButton('Зарегистрироваться', array('id' => "submit", 'class' => "inp_batton")); ?>


        <div class="clear"></div>


        <?php $this->endWidget(); ?>
    </div>



<?php else: ?>
    <p style="line-height: 18px;">
        Пользователь был успешно создан. Для подтверждения e-mail адреса, вы должны перейдя по ссылке указанной в письме, которое было отправлено Вам на почтовый адрес.
        <br />Если письмо вам не пришло, то проверьте его в СПАМе или вышлите повторно.
    </p>
<? endif; ?>

<div class="reg_hello">
                <div class="top"></div>
                <div class="center">
                    <div class="field">
			<?=Setting::getValue('registration_info')?>
                    </div>
                </div>
                <div class="bot"></div>

</div>



</div>