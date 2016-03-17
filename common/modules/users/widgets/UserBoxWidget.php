<?php
namespace common\modules\users\widgets;

use yii\base\Widget;
use Yii;

class UserBoxWidget extends Widget
{
    public $user;
    
    /**
	 * Executes the widget.
	 * This method is called by {@link CBaseController::endWidget}.
	 */
	public function run()
	{
        $this->setUser();
        
		echo $this->render('UserBoxWidget', ['user'=>$this->user]);
	}

    public function setUser()
    {
        $this->user = Yii::$app->user->getIdentity();
    }
}