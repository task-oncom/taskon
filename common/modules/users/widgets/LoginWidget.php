<?php
namespace common\modules\users\widgets;

use yii\base\Widget;
use common\models\LoginForm;

class LoginWidget extends Widget
{
    public $modelLogin;
    public $formLoginPopup;
    /**
	 * Executes the widget.
	 * This method is called by {@link CBaseController::endWidget}.
	 */
	public function run()
	{
        $modelLogin = new LoginForm();
        $formLoginPopup = new \common\components\BaseForm('/common/modules/users/forms/LoginPopupForm', $modelLogin);
		echo $this->render('LoginWidget', ['formLoginPopup' => $formLoginPopup->out]);
	}
}