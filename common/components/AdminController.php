<?php
namespace common\components; 

use Yii;
use yii\base\NotSupportedException;

use common\modules\users\models\User;

abstract class AdminController extends \common\components\BaseController
{
    public $layout = '//main';

    public $tabs;
	
	public static function getLocalId() {
		
		return str_replace('Controller', '', self::className());
	}

    public function init()
    {
		parent::init();

		if(Yii::$app->user->isGuest)
        {
			return $this->redirect('/site/login');
        }

        $module = $this->getModuleName();

		if(Yii::$app->user->identity->role != User::ROLE_ADMIN || ($module && !Yii::$app->authManager->checkAccess(Yii::$app->user->id, $module)))
    	{
    		throw new \yii\web\HttpException(403, 'У Вас нет прав для просмотра этой страницы');
    	}
    }

    private function getModuleName()
    {
    	if(isset($this->module) && $this->module->id)
        {
        	if($this->module->id == Yii::$app->id)
        	{
        		return null;
        	}

	    	switch ($this->module->id) 
	    	{
	    		case 'users':
	    			return 'rbac';
	    			break;
	    		
	    		default:
	    			return $this->module->id;
	    			break;
	    	}
	    }

	    return null;
    }
}
