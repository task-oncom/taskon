<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

use common\models\Settings;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_change_code]);
?>

Добрый день!

Вам был сброшен пароль для сайта <?=Yii::$app->params['frontUrl']?>.

Для продолжения работы перейдите по ссылке для ввода нового пароля:

<?= $resetLink ?>

В целях безопасности просим Вас не передавать пароль третьим лицам.

С уважением, команда Task-On.

Если у Вас есть вопросы обратитесь к администратору сервиса на адрес <?=Settings::getValue('content-support-email')?>