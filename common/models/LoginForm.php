<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\modules\users\models\User;
/**
 * Login form
 */
class LoginForm extends Model
{
    public $phone;
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = null;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            //[['phone'], 'required', 'message' => 'Укажи свой e-mail для того что бы зайти.'],
            [['username'], 'required', 'message' => 'Укажи свой e-mail для того чтобы зайти.'],
			[['password'], 'required', 'message' => 'Укажи свой пароль для того чтобы зайти.'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            ['phone','safe'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) 
        {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) 
            {
                $this->addError($attribute, 'Неверно указан e-mail или пароль. Проверьте правильность ввода.');
            }
            elseif($user->status == User::STATUS_BLOCKED)
            {
                $this->addError($attribute, 'У Вас нет прав для просмотра данного раздела. Обратитесь к Администратору для изменения параметров авторизации.');
            }
        }
    }
    
    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        $user = $this->getUser();

        if($user)
        {
            $user->last_logon = time();
            $user->save(false, ['last_logon']);

            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
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
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    public function attributeLabels() {
        return [
            'phone' => 'Телефон',
            'rememberMe' => '<small>Запомнить меня</small>',
        ];
    }

    public function formName() {
        return 'LoginForm';
    }
}
