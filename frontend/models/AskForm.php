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
class AskForm extends Model
{
    public $phone;
    public $name;
    public $email;
    public $ask = false;



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['phone'], 'required', 'message' => 'Укажите номер телефона'],
            //[['username'], 'required', 'message' => 'Укажи свой e-mail для того что бы зайти.'],
            [['name'], 'required', 'message' => 'Укажите своё имя'],
            // rememberMe must be a boolean value
            [['email'], 'required', 'message' => 'Укажите свой E-Mail'],
            [['email'], 'email', 'message' => 'Укажите корректный E-Mail'],
            [['ask'], 'required', 'message' => 'Укажите текст вопроса'],
        ];
    }

    public function formName() {
        return 'AskForm';
    }

    public function send() {
        return true;
    }
}
