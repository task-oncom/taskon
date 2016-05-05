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

        if(isset($this->module) && $this->module->id)
        {
        	if(!Yii::$app->authManager->checkAccess(Yii::$app->user->id, $this->module->id))
        	{
        		throw new \Exception('There is no access to this page', 403);
        	}
        }
    }
}
