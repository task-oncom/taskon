<?php $this->page_title = "Запрос на активацию аккаунта"; ?>

<h3 style="color:white;text-align: center">Запрос на активацию аккаунта</h3>

<?php
if (Yii::app()->user->hasFlash('done'))
{
    echo $this->msg(Yii::t('UsersModule.main', Yii::app()->user->getFlash('done')), 'ok');
}
else
{
    if ($error)
    {
        echo $this->msg(Yii::t('UsersModule.main', $error), 'error');
    }

    echo $form;

} ?>



<style type="text/css">
    label{width: 110px !important; float: none !important; display: inline-block !important;}
    input{width: 200px !important;}
    .buttons input{float: none !important;}
</style>