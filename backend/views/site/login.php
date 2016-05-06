<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

?>

<!-- begin login -->
<div class="login login-v2" data-pageload-addclass="animated flipInX">
    <!-- begin brand -->
    <div class="login-header">
        <div class="brand">
            <img src="/img/logo.png">
            <small>Авторизация</small>
        </div>
        <div class="icon">
            <i class="fa fa-sign-in"></i>
        </div>
    </div>
    <!-- end brand -->
    <div class="login-content">
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
						'username', 
						[
							'inputOptions' => [
								'class' => 'form-control input-lg',
								'placeholder' => 'Укажи свой e-mail для того чтобы зайти',
							]
						]
					)->label(false) ?>
            </div>
            <div class="form-group m-b-20" style="margin-bottom: 0 !important;  margin-bottom: 0;">
                <?= $form->field(
						$model, 
						'password', 
						[
							'inputOptions' => [
								'class' => 'form-control input-lg',
								'placeholder' => 'Пароль',

							],
                            'options' => [
                                'style' => 'margin-bottom: 0 !important;',
                            ],
						]
					)->passwordInput()->label(false) ?>
            </div>
            <div class="checkbox m-b-20" style="margin-top: 0; margin-bottom: 0 !important;">
                <label>
                    <?= $form->field(
							$model, 
							'rememberMe',
							[
								'labelOptions'=>['style'=>'padding-left: 0;']
							]
							)->checkbox()
							->label('Запомнить мой компьютер.') ?>
                </label>
            </div>
            <div class="login-buttons">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-success btn-block btn-lg', 'name' => 'login-button']) ?>
            </div>
            <div class="m-t-20">
                Забыли свой пароль? Нажмите <?=Html::a('здесь', ['recovery'])?> чтобы восстановить.
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<!-- end login -->

<?php
	
	$this->registerJsFile('/js/login-v2.demo.min.js', ['position' => \yii\web\View::POS_END ]);
	// $this->registerJsFile('/js/apps.min.js', ['position' => \yii\web\View::POS_END ]);
	$this->registerJs('App.init();LoginV2.init();',  \yii\web\View::POS_READY);
?>