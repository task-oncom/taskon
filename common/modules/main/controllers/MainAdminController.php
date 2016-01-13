<?php

namespace common\modules\main\controllers;

use common\modules\request\models\CronWork;
use common\models\Settings;

class MainAdminController extends \common\components\AdminController
{
    //public $layout = '\main';
	
	public static function actionsTitles() 
    {
        return array(
            'Index'            => 'Просмотр главной страницы',
            'Modules'          => 'Просмотр списка модулей',
            'ChangeOrder'      => 'Сортировка',
            'SessionPerPage'   => 'Установки кол-ва элементов на странице',
            'SessionLanguage'  => 'Установка языка',
            'AdminLinkProcess' => 'Переход по ссылке в админ панель',
            'Calculatepercent' => '',
        );
    }
	
	public function actionIndex()
    {
		return $this->render('index');
    }

    public function actionCalculatepercent()
    {
        \console\controllers\PercentController::actionCalculate();
        return $this->redirect('index');
    }
	
	public function actionSessionPerPage($model, $per_page, $back_url)
    {die($model.' -- '.$per_page.'  --  '.$back_url);
        \Yii::$app->session["{$model}PerPage"] = $per_page;

        $this->redirect(base64_decode($back_url));
    }

}
