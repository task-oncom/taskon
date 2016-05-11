<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

use common\models\Settings;

?>

Добрый день!

Уведомляем Вас о том, что Вы были зарегистрированы на сайте <?=Settings::getValue('setting-project-name')?>.
Для входа используйте следующие пароли доступа:

Логин: <?= $user->email; ?>

Пароль: <?= $user->password; ?>

В целях безопасности просим Вас не передавать пароль третьим лицам.

С уважением, команда <?=Settings::getValue('setting-project-name')?>.

Если у Вас есть вопросы обратитесь к администратору сервиса на адрес <?=Settings::getValue('content-support-email')?>