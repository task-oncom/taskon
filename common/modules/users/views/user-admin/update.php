<style>
    #user-password + a, #user-password_c + a{
        display: none;
    }
</style>
<?php
echo $form;
$url_block = \yii\helpers\Url::toRoute(['/users/user-admin/block', 'id'=>$model->id]);
$url_delete = \yii\helpers\Url::toRoute(['/users/user-admin/delete', 'id'=>$model->id]);
$script = <<< JS

$('#block').on('click', function(e) {
    document.location.href = "$url_block";
});
$('#delete').on('click', function(e) {
    if(prompt('Вы действительно хотите удалить пользователя\\nНАВСЕГДА и все его данные из системы?\\nВведите пароль на удаление: ') == '25350')
        document.location.href = "$url_delete";
    else alert('Неверный пароль');
});

JS;
$this->registerJs($script);
$status = \yii::t('users',$model->status);
$active = \yii::t('users','active');
$block = \yii::t('users','blocked');
$script = <<< JS

    "use strict";
    $("#user-phone").mask("8(999) 999-9999");
    $("#user-mobile_phone").mask("8(999) 999-9999");
    $('#user-password').passwordStrength({targetDiv: '#passwordStrengthDiv'});
    $('#user-password_c').passwordStrength({targetDiv: '#passwordStrengthDiv2'});

    $('[data-id="switchery-state-text"]').text('$status');
    $('[data-change="check-switchery-state-text"]').live('change', function() {
        if($(this).prop('checked'))
            $('[data-id="switchery-state-text"]').text('$active');
        else
            $('[data-id="switchery-state-text"]').text('$block');
    });

JS;
$this->registerJs($script);
?>
<?php $this->registerJsFile('/plugins/masked-input/masked-input.min.js', ['position' => \yii\web\View::POS_END]);?>
<?php $this->registerJsFile('/js/form-plugins.demo.min.js', ['position' => \yii\web\View::POS_HEAD]);?>
<?php $this->registerCssFile('/plugins/password-indicator/css/password-indicator.css', ['position' => \yii\web\View::POS_HEAD]);?>