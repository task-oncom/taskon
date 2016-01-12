<?php

namespace common\modules\reviews;

class Module extends \common\components\WebModule
{
    public $controllerNamespace = 'common\modules\reviews\controllers';
	
	public $menu_icons = 'fa fa-comment-o';

    public static $active = true;
		
	
    public static $base_module = true;


    public static function name()
    {
        return 'Отзывы';
    }


    public static function description()
    {
        return 'Данный модуль позволяет настраивать отображение на сайте текстового материалов на различных языках. Для добавления нового языка нажмите «Добавить новый язык».';
    }


    public static function version()
    {
        return '1.0';
    }
	
	public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
	
	public static function adminMenu()
    {
        return array(
            'Список отзывов'      => '/reviews/review-admin/manage',
        );
    }
}
