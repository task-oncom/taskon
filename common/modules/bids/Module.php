<?php

namespace common\modules\bids;

/**
 * bids module definition class
 */
class Module extends \common\components\WebModule
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\bids\controllers';

    public static $active = true;
    
    public $menu_icons = 'fa fa-inbox';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    public static function name()
    {
        return 'Управление заявками';
    }


    public static function description()
    {
        return 'Управление заявками';
    }


    public static function version()
    {
        return '1.0';
    }
    
    public static function adminMenu()
    {
        return array(

            'Список заявок'                => '/bids/bid-admin/manage',
        );
    }
}
