<?php
namespace common\modules\users;

class users extends \common\components\WebModule
{
    public static $active = true;

    public static $base_module = true;
	
	public $menu_icons = 'fa fa-users';

    public static function name()
    {
        return 'Пользователи';
    }


    public static function description()
    {
        return 'Зарегистрированные пользователи';
    }


    public static function version()
    {
        return '1.0';
    }


	public function init()
	{
		parent::init();
//        SchneiderHelper::encodeDetecting('Diana.Leyba@ru.schneider-electric.com');
//        Y::end();
		/*$this->setImport(array(
			'users.models.*',
			'users.components.*',
		));*/
		//die($this->controller->id);
	}

    public static function adminMenu()
    {
        return array(
            'Зарегистрированные' => '/users/user-admin/manage',
//          'Добавить пользователя' => '/users/user-admin/create',
        );
    }
}
