<?php

namespace common\modules\main;


//class main extends \yii\base\Module
class main extends \common\components\WebModule
{
    public $controllerNamespace = 'common\modules\main\controllers';
	public static $base_module = true;
	
	public $menu_icons = 'fa fa-wrench';

    public function init()
    {
        parent::init();
		
        // custom initialization code goes here
    }
	
	


    public static function description()
    {
        return 'Главный модуль';
    }


    public static function version()
    {
        return '1.0';
    }


    public static function name()
    {
        return 'Настройки системы';
    }

    public static function adminMenu()
    {
        return array(
            'Настройки'             => '/main/settings/manage',
        );
    }
	
	public static function saveSiteAction()
    {
        $action = \Yii::$app->controller->action;
        if (ucfirst($action->id) == 'Error')
        {
            return;
        }
        $action_titles = call_user_func(array(get_class(\Yii::$app->controller), 'actionsTitles'));

        if (!isset($action_titles[ucfirst($action->id)]))
        {
            throw new CHttpException('Не найден заголовок для дейсвия ' . ucfirst($action->id));
        }

        $title = $action_titles[ucfirst($action->id)];


        $site_action             = new SiteAction();
        $site_action->title      = $title;
        $site_action->module     = $action->controller->module->id;
        $site_action->controller = $action->controller->id;
        $site_action->action     = $action->id;

        if (!\Yii::$app->user->isGuest)
        {
            self::fillInterval();

            $site_action->user_id   = \Yii::$app->user->id;
            $site_action->user_role = \Yii::$app->user->getRole();
        }

        $site_action->save();
    }
}
