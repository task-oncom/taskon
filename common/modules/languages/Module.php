<?php

namespace common\modules\languages;

class Module extends \common\components\WebModule
{
    public $controllerNamespace = 'common\modules\languages\controllers';
	
	public $menu_icons = 'fa fa-language';

    public static $active = true;
		
	
    public static $base_module = true;


    public static function name()
    {
        return 'Управление языками';
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
            'Список языков'      => '/languages/language-admin/manage',
        );
    }
}
