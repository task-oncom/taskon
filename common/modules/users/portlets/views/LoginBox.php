



<?php if(Yii::app()->user->isGuest) { ?>
<div class="enter">
    <a id="login_box_display" href="#"><span></span>Личный кабинет</a>
    <div class="enter_reg">
    <div class="auth_window">
        <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id'=>'login-form',
                'action' => '/login',
                'enableAjaxValidation'=>true,
                'htmlOptions' => array(
                    'class' => 'authorization search_2',
                ),
            ));
            $model=new LoginForm($form);
        ?>

        <p>ВОЙТИ НА САЙТ <a class="exit_batton" href="#"></a></p>
        <div class="clear"></div>
        <label for="">Email</label>
        <?php echo $form->textField($model,'email', array('class' => 'no_border')); ?>
        <dl class="message"><?php echo $form->error($model,'email', array('class' => 'emailL error')); ?></dl>
        <label  for="">Пароль</label>
        <?php echo $form->passwordField($model,'password', array('class' => 'no_border')); ?>
        <dl class="message"><?php echo $form->error($model,'password', array('class' => 'passL error')); ?></dl>
        <a href="#" class="forgot_my_password">Забыли пароль?</a>
        <input type="submit" value="ВОЙТИ" />
        <div class="clear"></div>
        <a class="reg" href="/registration">Еще не зарегистрировались?</a>

        <?php $this->endWidget();?>
    </div>

    <div class="recover_password_window" style="display:none;">
        <?php
        $form_2=$this->beginWidget('CActiveForm', array(
            'id'=>'recover-form',
            'action' => '/users/user/RecoverPassword',
            'htmlOptions'=>array( 'class' => 'authorization search_2',)
        ));
        $model_2=new LoginForm($form_2);
        ?>

        <a class="exit_batton" href="#"></a></p>
        <p><?php echo $form_2->labelEx($model_2,'E-mail:'); ?></p>
        <p class="item"><?php echo $form_2->textField($model_2,'email'); ?></p>
        <p class="message"><?php echo $form_2->error($model_2,'email'); ?></p>
        <p><?php echo CHtml::submitButton('Отправить',array('class'=>'ask_button')); ?></p>
        <p><a href="" class="to_auth_user">Авторизоваться</a></p>

    </div>

    <?php

    $this->endWidget();

    $script = "
			$('.forgot_my_password').click(function(){
				$('.auth_window').fadeOut(500, function(){ $('.recover_password_window').fadeIn(500); });
				return false;
			});
			$('.to_auth_user').click(function(){
				$('.recover_password_window').fadeOut(500, function(){ $('.auth_window').fadeIn(500); });
				return false;
			});
		";

    Yii::app()->clientScript->registerScript('', $script, CClientScript::POS_END);

    ?>

    </div>
</div>

<?php } else { ?>

    <div class="User"><a href="/cabinet"><?=Yii::app()->user->login?></a> <a href="/logout">выйти</a></div>

<?php } ?>






