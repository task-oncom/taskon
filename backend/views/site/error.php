<?php

use yii\helpers\Html;
use common\models\Settings;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

?>
<!-- begin #page-loader -->
<div id="page-loader" class="fade in"><span class="spinner"></span></div>
<!-- end #page-loader -->

<!-- begin #page-container -->
<div id="page-container" class="fade">
    <!-- begin error -->
    <div class="error">
        <div class="error-code m-b-10"><?=($exception?$exception->statusCode:'404')?> <i class="fa fa-warning"></i></div>
        <div class="error-content">
            <div class="error-message"><?=($exception?$exception->getMessage():'Произошла какая-то ошибка')?></div>
            <div class="error-desc m-b-20">
                Страница не существует или у вас нет прав для ее просмотра.<br />
                Проверьте введенный URL-адрес страницы или обратитесь в Службу технической поддержки для решения данного вопроса
                <?=Html::a(Settings::getValue('content-support-email'), 'mailto:'.Settings::getValue('content-support-email'))?>
            </div>
            <div>
                <a href="/" class="btn btn-success">Вернуться на главную страницу</a>
            </div>
        </div>
    </div>
    <!-- end error -->
</div>
<!-- end page container -->
