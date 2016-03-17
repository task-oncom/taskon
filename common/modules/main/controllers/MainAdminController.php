<?php

namespace common\modules\main\controllers;

class MainAdminController extends \common\components\AdminController
{
    //public $layout = '\main';
	
	public static function actionsTitles() 
    {
        return array(
            'Index'            => 'Просмотр главной страницы',
        );
    }
	
	public function actionIndex()
    {
		return $this->render('index');
    }

}
