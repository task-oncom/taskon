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

		if(Yii::$app->user->identity->role != User::ROLE_ADMIN)
        {
			throw new NotSupportedException('The requested page does not exist.');
        }

        $module = $this->getModuleName();

		if($module && !Yii::$app->authManager->checkAccess(Yii::$app->user->id, $module))
    	{
    		throw new \Exception('There is no access to this page', 403);
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
