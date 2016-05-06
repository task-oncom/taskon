<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<!-- begin login -->
<div class="login login-v2" data-pageload-addclass="animated flipInX">
    <!-- begin brand -->
    <div class="login-header">
        <div class="brand">
            <img src="/img/logo.png">
            <small>Востановление пароля</small>
        </div>
        <div class="icon">
            <i class="fa fa-sign-in"></i>
        </div>
    </div>
    <!-- end brand -->
    <div class="login-content">
    	<?php if($success) : ?>
			<center>
				Новый пароль успешно сохранен. <br>
				<?=Html::a('Вернуться к авторизации', ['login'])?>
			</center>
    	<?php else : ?>
	        <?php $form = ActiveForm::begin([
				'enableClientValidation' => true,
				'id' => 'login-form', 
				'options' => [
					'class' => 'margin-bottom-0'
				],
				'fieldConfig' => [
					'template' => '{input}{error}',
				],
			]); ?>
	            <div class="form-group m-b-20">
	            <?= $form->field(
							$model, 
							'password', 
							[
								'inputOptions' => [
									'class' => 'form-control input-lg',
									'placeholder' => 'Введите новый пароль',
								]
							]
						)->passwordInput()->label(false) ?>
	            </div>
	            
	            <div class="login-buttons">
	                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-block btn-lg', 'name' => 'login-button']) ?>
	            </div>
	        <?php ActiveForm::end(); ?>
	    <?php endif; ?>
    </div>
</div>
<!-- end login -->

<?php
$this->registerJsFile('/js/login-v2.demo.min.js', ['position' => \yii\web\View::POS_END ]);
$this->registerJs('App.init();LoginV2.init();',  \yii\web\View::POS_READY);
?>