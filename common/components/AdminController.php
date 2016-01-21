<?php
use \common\components;
namespace common\components; 

abstract class AdminController extends \common\components\BaseController
{
    public $layout='//main';

    public $tabs;
	
	public static function getLocalId() {
		
		return str_replace('Controller', '', self::className());
	}

    public function init()
    {
		parent::init();

        $admin_url = $this->url('/users/userAdmin/login');
		
		if(\Yii::$app->user->isGuest)
        {
			$this->redirect('/site/login');
            \Yii::$app->end();
        }

		if(\Yii::$app->user->identity->getRole() == 'user')
			$this->redirect('/');
        if (\Yii::$app->user->isGuest && $_SERVER['REQUEST_URI'] != $admin_url)
        {
            $this->redirect($admin_url);
        }

        $this->view->registerJsFile('/js/packages/adminBaseClasses/buttonSet.js');
		$this->view->registerJsFile('/js/packages/adminBaseClasses/gridBase.js');

    }
}
