<?php
use yii\helpers\Html;
use yii\web\View;

?>
<?php if (!Yii::$app->user->isGuest):?>
    <div class="prof_block"> <span class="prof_name"><?php echo $user->name ?></span>
    <?php echo Html::a('Выход', ['/site/logout']) ?></div>
<?php else:?>
    <div class="prof_block">
        <a href="#registration_form2" class="popup-form">Вход</a>
    </div>
<?php endif;?>