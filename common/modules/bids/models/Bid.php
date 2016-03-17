<?php

namespace common\modules\bids\models;

use Yii;

use common\models\Settings;
use common\modules\sessions\models\Session;

/**
 * This is the model class for table "bids".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $file
 * @property string $text
 */
class Bid extends \common\components\ActiveRecordModel
{
    const SCENARIO_PROJECT = 'project';
    const SCENARIO_CALLBACK = 'callback';
    const SCENARIO_SUBSCRIBE = 'subscribe';

    const FORM_SUBSCRIBE = 'subscribe';
    const FORM_CALLBACK = 'callback';
    const FORM_PROJECT = 'project';
    const FORM_MESSAGE = 'message';

    const TAG_INVOLVEMENT = 'Вовлечение';
    const TAG_TREATMENT = 'Обращение';

    public static $form_titles = [
        self::FORM_PROJECT => 'Расчитать проект',
        self::FORM_CALLBACK => 'Обратный звонок',
        self::FORM_SUBSCRIBE => 'Подписка',
        self::FORM_MESSAGE => 'Сообщение с сайта',
    ];

    public static $tag_titles = [
        self::TAG_INVOLVEMENT => 'Вовлечение',
        self::FORM_SUBSCRIBE => 'Ошибки',
    ];

    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bids';
    }

    /**
     * @inheritdoc
     */
    public function name() 
    {
        return 'Заявки';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['phone', 'required', 'when' => function($model) {
                return !$model->email;
            }, 'on' => self::SCENARIO_PROJECT],
            ['email', 'required', 'when' => function($model) {
                return !$model->phone;
            }, 'on' => self::SCENARIO_PROJECT],

            [['email'], 'email'],

            [['name', 'phone'], 'required', 'on' => self::SCENARIO_CALLBACK],

            [['email'], 'required', 'on' => self::SCENARIO_SUBSCRIBE],

            
            [['text'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 30],
            [['email'], 'string', 'max' => 70],
            [['form'], 'string', 'max' => 50],
            [['file'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'name' => 'Имя',
            'phone' => 'Телефон',
            'email' => 'Email',
            'text' => 'Сообщение',
            'form' => 'Форма отправки',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(BidFile::className(), ['bid_id' => 'id']);
    }    

    public function send()
    {
        try 
        {
            $session = null;
            if(Yii::$app->session->has('SessionId'))
            {
                $session = Session::findOne(Yii::$app->session->get('SessionId'));
            }        

            $email = Settings::getValue('bids-support-email');

            $message = Yii::$app->controller->view->render('@common/modules/bids/views/bid/mail-all', [
                'model' => $this,
                'session' => $session,
            ]);

            $headers = "MIME-Version: 1.0\r\n".
                "Content-Transfer-Encoding: 8bit\r\n".
                "Content-Type: text/html; charset=\"UTF-8\"\r\n".
                "X-Mailer: PHP v.".phpversion()."\r\n".
                "From: Заявка с сайта TaskOn <".Settings::getValue('bids-support-email-from').">\r\n";

            $subject = "Заявка с сайта TaskOn";

            @mail($email, $subject, $message, $headers);
        } 
        catch (Exception $e) 
        {
        }
    }
}
