<?php

namespace common\modules\faq;

class Module extends \common\components\WebModule
{
    public $controllerNamespace = 'common\modules\faq\controllers';
	
	public $menu_icons = 'fa fa-question';

    public static $active = true;
		
	
    public static $base_module = true;


    public static function name()
    {
        return 'Вопрос-ответ';
    }


    public static function description()
    {
        return 'Вопрос-ответ';
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
            'Список вопросов и ответов'      => '/faq/faq-admin/manage',
        );
    }
}
