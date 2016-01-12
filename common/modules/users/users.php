<?php
namespace common\modules\users;

class users extends \common\components\WebModule
{
   public static $active = true;
		
	
    public static $base_module = true;
	
	public $menu_icons = 'fa fa-users';

    public static function name()
    {
        return 'Кредитование';
    }


    public static function description()
    {
        return '1.	В данном разделе отображается реестр «плохих», «хороших» и «не определенных» заемщиков. Новые заемщик могут попасть в данный реестр автоматически, если заполнили скоринговую карту на сайте или вручную ответственным сотрудником. Для добавления нового заемщика вручную нажмите на кнопку «Добавить нового заемщика»
2.	Данный раздел хранит информацию о мошенниках, которые обманули или могут обмануть финансовую организацию. Для внесения данных о мошенниках нажмите на кнопку «Добавить нового мошенника». 
';
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

    /*public static function adminMenu()
    {
        return array(
            'Список заемщиков'      => '/users/user-admin/manage',
            'Черный список заемщиков'=> '/users/user-admin/manage/is_deleted/1',
//            'Добавить пользователя' => '/users/user-admin/create',
//            'Импорт из CSV-файла'   => '/users/user-admin/importCSV'
        );
    }*/
}
