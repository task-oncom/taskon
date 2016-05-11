<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

use common\models\Settings;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_change_code]);
?>

Добрый день!

Для Вас был сброшен пароль на сайте <?=Settings::getValue('setting-project-name')?>.

Для того что бы задать новый пароль перейдите по ссылке ниже. Если ссылка не открывается, то скопируйте ее в адресную строку браузера.

<?= $resetLink ?>

В целях безопасности просим вас не передавать данную ссылку третьим лицам и не хранить данное письмо после сброса пароля.

С уважением, команда <?=Settings::getValue('setting-project-name')?>.

Если у Вас есть вопросы обратитесь к администратору сервиса на адрес <?=Settings::getValue('content-support-email')?>