<?php
namespace common\models;

use Yii;
use yii\base\Model;

use common\models\Settings;
use common\modules\users\models\User;

class RecoveryForm extends Model
{
    public $email;

    private $_user = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            [['email'], 'required', 'message' => 'Укажи свой e-mail для восстановления пароля.'],
            [['email'], 'email', 'message' => 'Некорректный формат.'],
            ['email', 'validateEmail'],
        ];
    }


    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) 
        {
            $user = $this->getUser();
            if (!$user) 
            {
                $this->addError($attribute, 'Такой пользователь не найден.');
            }
        }
    }
    
    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function recovery()
    {
        $user = $this->getUser();

        if (!User::isPasswordResetTokenValid($user->password_change_code)) 
        {
            $user->generatePasswordResetToken();
        }

        if ($user->save()) 
        {
            return Yii::$app->mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user])
                ->setFrom(Settings::getValue('content-support-email'))
                ->setTo($this->email)
                ->setSubject('Восстановление пароля')
                ->send();
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === null) 
        {
            $this->_user = User::findOne([
                'status' => User::STATUS_ACTIVE,
                'email' => $this->email,
            ]);
        }

        return $this->_user;
    }

    public function attributeLabels() 
    {
        return [
            'email' => 'E-mail',
        ];
    }
}
