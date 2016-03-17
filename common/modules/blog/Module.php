<?php

namespace common\modules\blog;

/**
 * blog module definition class
 */
class Module extends \common\components\WebModule
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\blog\controllers';

    public $menu_icons = 'fa fa-file-o';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public static function description()
    {
        return 'Блог';
    }

    public static function version()
    {
        return '1.0';
    }

    public static function name()
    {
        return 'Управление блогом';
    }

    public static function adminMenu()
    {
        return array(
            'Новая запись'             => '/blog/post-admin/create',
            'Записи'             => '/blog/post-admin/manage',
            'Теги'             => '/blog/tag-admin/manage',
        );
    }
}
