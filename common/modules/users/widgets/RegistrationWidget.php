<?php
namespace common\modules\users\widgets;

use yii\base\Widget;
use common\modules\users\models\User;

class RegistrationWidget extends Widget
{
    public $modelUser;
    public $formPopup;
    /**
	 * Executes the widget.
	 * This method is called by {@link CBaseController::endWidget}.
	 */
	public function run()
	{
        $modelUser = new User();                
        $formPopup = new \common\components\BaseForm('/common/modules/users/forms/RegistrationUserPopupForm', $modelUser);
		echo $this->render('RegistrationWidget', ['formPopup' => $formPopup->out]);
	}
}