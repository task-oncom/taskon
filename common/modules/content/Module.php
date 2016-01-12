<?php

namespace common\modules\content;

class Module extends \common\components\WebModule
{
    public $controllerNamespace = 'common\modules\content\controllers';

    public static $active = true;
	
	public $menu_icons = 'fa fa-file-o';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
	
	public static function name()
    {
        return 'Управление контентом';
    }


    public static function description()
    {
        return 'Управление контентом';
    }


    public static function version()
    {
        return '1.0';
    }
	
	public static function adminMenu()
    {
        return array(

            'Список страниц'      		    => '/content/content-admin/manage',
            'Инфо-блоки страниц'	        => '/content/block-admin/manage',
            'Управление категориями'        => '/content/category-admin/manage',
            'Контактные данные организации' => '/content/settings/manage',
        );
    }
}
