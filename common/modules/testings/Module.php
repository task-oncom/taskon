<?php

namespace common\modules\testings;

class Module extends \common\components\WebModule
{
    public $controllerNamespace = 'common\modules\testings\controllers';
	
	public $menu_icons = 'fa fa-question';

    public static $active = true;
		
	
    public static $base_module = true;


    public static function name()
    {
        return 'Тестирование';
    }


    public static function description()
    {
        return 'Тестирование';
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
            'Список сессий'      => '/testings/testing-session-admin/manage',
            'Список тестов'      => '/testings/testing-test-admin/manage',
            'Список вопросов'      => '/testings/testing-question-admin/manage',
        );
    }
}
