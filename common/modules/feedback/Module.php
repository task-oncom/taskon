<?php

namespace common\modules\feedback;

class Module extends \common\components\WebModule
{
    public $controllerNamespace = 'common\modules\feedback\controllers';
	
	public $menu_icons = 'fa fa-bullhorn';

    public static $active = true;
		
	
    public static $base_module = true;


    public static function name()
    {
        return 'Форма обратной связи';
    }


    public static function description()
    {
        return 'В данном разделе отображается список полученных сообщений с сайта, которые были оставлены через форму обратной связи. ';
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
            'Полученные сообщения'      => '/feedback/feedback-admin/manage',
        );
    }
}
