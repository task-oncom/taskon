<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 05.04.2015
 * Time: 18:26
 */


namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use common\modules\scoring\models\ScClient;
/**
 * Login form
 */
class LoginForm extends Model
{
    public $phone;
    public $username;
    public $password;
    public $rememberMe = false;

    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['phone'], 'required', 'message' => 'Указанный номер телефона не найден. Если у вас нет личного кабинета, то '.Html::a('зарегистрируйтесь', '/scoring/register/register')],
            //[['username'], 'required', 'message' => 'Укажи свой e-mail для того что бы зайти.'],
            [['password'], 'required', 'message' => 'Укажи свой пароль от личного кабинета'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            [['phone', 'username'],'safe'],
            // password is validated by validatePassword()
            ['password', 'validatePassword', 'message' => 'Логин или пароль указан не верно. Проверьте корректность веденого логина и пароля.'],
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
        if (!$this->hasErrors()) {
            $user = $this->getUser();
//die(print_r($user->validatePassword($this->password)));
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверно указан телефон или пароль. Проверьте правильность ввода.');
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
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {

            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = ScClient::findByUsername($this->phone);
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
