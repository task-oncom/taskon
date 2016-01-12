<?php

namespace common\modules\reviews\models;

use Yii;

/**
 * This is the model class for table "reviews".
 *
 * @property string $id
 * @property string $lang
 * @property string $user_id
 * @property string $title
 * @property string $text
 * @property string $good
 * @property string $bad
 * @property string $admin_id
 * @property string $answer
 * @property string $photo
 * @property string $state
 * @property string $date
 * @property string $date_create
 * @property integer $priority
 * @property string $email
 * @property string $notification_date
 * @property integer $notification_send
 * @property integer $order
 * @property string $attendant_products
 * @property integer $cat_id
 * @property integer $show_in_module
 * @property integer $rate_usability
 * @property integer $rate_loyality
 * @property integer $rate_profit
 */
class Reviews extends \common\components\ActiveRecordModel
{
    private static $rate = [
        'rate_usability' => [
            0 => 'Не определено',
            1 => 'Есть, что доработать в сервисе',
            2 => 'Возникли трудности с использованием',
            3 => 'Можно пользоваться',
            4 => 'Все просто и удобно',
            5 => 'Очень удобно',
        ],
        'rate_loyality' => [
            0 => 'Не определено',
            1 => 'Остался нейтрален',
            2 => 'Учитывают интересы заемщика',
            3 => 'Идут на встречу',
            4 => 'Все понравилось, обращусь еще',
            5 => 'Очень хорошие впечатления от сервиса',
        ],
        'rate_profit' => [
            0 => 'Не определено',
            1 => 'Не достаточно выгодно',
            2 => 'Нормальные условия',
            3 => 'Достаточно выгодно',
            4 => 'Выгодно',
            5 => 'Отличные условия по рынку!',
        ],
    ];

    public static function getSource($type) {
        return self::$rate[$type];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reviews';
    }

    public function name() {
        return 'Отзывы';
    }

    // public function getUser() {
    //     return $this->hasOne(\common\modules\scoring\models\ScClient::className(), ['id' => 'user_id']);
    // }

    public function getOperator() {
        return $this->hasOne(\common\modules\users\models\User::className(), ['id' => 'admin_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'text', 'state', 'date', 'notification_send', 'show_in_module'], 'required'],
            [['user_id', 'admin_id', 'priority', 'notification_send', 'order', 'cat_id', 'show_in_module', 'rate_usability', 'rate_loyality', 'rate_profit'], 'integer'],
            [['text', 'state', 'attendant_products'], 'string'],
            [['admin_id'], 'adminIdValidate'],
            [['date', 'answer', 'good', 'bad', 'date_create', 'notification_date', 'rate_usability', 'rate_loyality', 'rate_profit', 'title', 'order'], 'safe'],
            [['lang'], 'string', 'max' => 2],
            [['title'], 'string', 'max' => 250],
            [['photo'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 255]
        ];
    }

    public function adminIdValidate($attr, $value) {
        if(empty($this->answer))
            if(empty($this->$attr))
                $this->addError($attr, 'Выберите опреатора');

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('reviews', 'ID'),
            'lang' => Yii::t('reviews', 'Язык'),
            'user_id' => Yii::t('reviews', 'Автор'),
            'admin_id' => Yii::t('reviews', 'Оператор'),
            'title' => Yii::t('reviews', 'Заголовок'),
            'text' => Yii::t('reviews', 'Отзыв'),
            'answer' => Yii::t('reviews', 'Ответ'),
            'good' => Yii::t('reviews', 'Понравилось'),
            'bad' => Yii::t('reviews', 'Не понравилось'),
            'photo' => Yii::t('reviews', 'Фото'),
            'state' => Yii::t('reviews', 'Состояние'),
            'date' => Yii::t('reviews', 'Дата'),
            'date_create' => Yii::t('reviews', 'Создана'),
            'priority' => Yii::t('reviews', 'Priority'),
            'email' => Yii::t('reviews', 'Email'),
            'notification_date' => Yii::t('reviews', 'Notification Date'),
            'notification_send' => Yii::t('reviews', 'Notification Send'),
            'order' => Yii::t('reviews', 'Order'),
            'attendant_products' => Yii::t('reviews', 'Attendant Products'),
            'cat_id' => Yii::t('reviews', 'Публиковать на странице категории'),
            'show_in_module' => Yii::t('reviews', 'Отображать на Главной'),
            'rate_usability' => Yii::t('reviews', 'Удобство'),
            'rate_loyality' => Yii::t('reviews', 'Лояльность'),
            'rate_profit' => Yii::t('reviews', 'Выгода'),
        ];
    }

    public function hasComment() {
        if(!empty($this->answer))
            return true;
        return false;
    }

    public function getRate($param) {
        $return = [];
        $return['width'] = (int)$this->$param * 20;
        $return['tooltip'] = self::$rate[$param][(int)$this->$param];
        //if($this->id == 681) die(print_r($return));
        return $return;
    }

    public function truncateAnswer($chars = 25) 
    {
        $text = $this->answer . " ";
        $text = substr($text, 0, $chars);
        $text = substr($text, 0, strrpos($text, ' '));
        $text = $text . "...";
        return $text;
    }

    private static function getMaxOrder(){
        $ret = 1;
        $model = self::find()->orderBy('order DESC')->one();
        if(!empty($model))
            $ret = $model->order;

        return $ret;
    }

    public function beforeValidate() {
        if(!empty($this->answer) && empty($this->admin_id)) $this->admin_id = \yii::$app->user->id;
        if(empty($this->order))
            $this->order = self::getMaxOrder();
        if(empty($this->show_in_module))
            $this->show_in_module = 1;
        if(empty($this->date))
            $this->date = date('Y-m-d');
        if(empty($this->notification_send))
            $this->notification_send = '0';

        return parent::beforeValidate();
    }

    public function beforeSave($insert) 
    {
        if($this->date) {
            $this->date = date('Y-m-d', strtotime($this->date));
        }

        return parent::beforeSave($insert);
    }
}
