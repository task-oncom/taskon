<?php $this->page_title = "Изменение пароля"; ?>
<h2 style="color: white; text-align: center" >Смена пароля</h2>
    <br/>
<?php if (isset($error)): ?>
    <?php echo Yii::t('UsersModule.main', $this->msg($error, 'error')); ?>
<?php else: ?>
    <?php echo $form; ?>
<?php endif ?>

<style type="text/css">
    label{width: 128px !important;}
    .buttons input{float: none !important;}
</style>