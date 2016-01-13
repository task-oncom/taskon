<?php

namespace common\modules\faq\models;

use Yii;
use \common\components\validators\TransliterateValidator;
use \yii\helpers\StringHelper;
use \common\models\MetaTags;
/**
 * This is the model class for table "faq".
 *
 * @property integer $id
 * @property string $lang
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property integer $cat_id
 * @property string $question
 * @property string $answer
 * @property integer $is_published
 * @property string $welcome
 * @property string $notification_date
 * @property integer $notification_send
 * @property integer $order
 * @property string $show_in_module
 * @property integer $view_count
 * @property string $url
 * @property integer $created_at
 * @property integer $updated_at
 */
class Faq extends \common\components\ActiveRecordModel
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'meta' => [
                'class' => 'common\components\activeRecordBehaviors\MetaTagBehavior',
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetaTags() {
        return $this->hasOne(MetaTags::className(), [
            'object_id' => 'id',
            /*            */
        ])->where([
            'model_id'  => get_class($this),
            'language' => 'ru',]);

    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'faq';
    }

    public function name() {
        return 'Вопрос-Ответ';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'is_published', 'notification_send', 'order', 'view_count', 'created_at', 'updated_at'], 'integer'],
            [['question', 'answer'], 'string', 'message' => 'Поле заполненно не корректно'],
            [['notification_date'], 'safe'],
            [['lang'], 'string', 'max' => 2],
            [['name', 'welcome', 'show_in_module'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 50],
            [['email'], 'email', 'message' => 'Поле заполненно не корректно'],
            //[['email', 'name', 'question'], 'required', 'message' => 'Необходимо заполнить'],
            [['question'], 'required', 'message' => 'Введите текст вашего сообщения'],
            /*[['email'], 'validate_email'],
            [['phone'], 'validate_phone'],*/

            [['name'], 'required', 'when' => function($model){return empty($model->name);}, 'message' => 'Укажите ваше имя'],
            [['phone'], 'required', 'when' => function($model){return empty($model->email);}, 'message' => 'Укажите ваш номер телефона'],
            [['email'], 'required', 'when' => function($model){return empty($model->phone);}, 'message' => 'Укажите ваш E-mail адрес'],
            [['url'], TransliterateValidator::className(), 'translitAttribute' => 'url'],
        ];
    }

    public function scenarios(){
        $scenarios = parent::scenarios();
        $scenarios['ClientSide'] = ['validate_phone','validate_email','email','phone','question','name','url'];//Scenario Values Only Accepted
        return $scenarios;
    }


    public function validate_phone($attribute, $params) {
        if(empty($this->email)) {
            $this->addError($attribute, 'Заполните одно из полей');
        }
    }

    public function validate_email($attribute, $params) {
        if( empty($this->phone)) {
            $this->addError($attribute, 'Заполните одно из полей');
        }
    }

    public function getShortQuestion() {
        return StringHelper::truncateWords($this->question, 10, '', true);
    }

    public function getShortAnswer() 
    {
        return StringHelper::truncateWords(strip_tags($this->answer, 'div'), 28, '...', true);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('faq', 'ID'),
            'lang' => Yii::t('faq', 'Language'),
            'name' => Yii::t('faq', 'Name'),
            'phone' => Yii::t('faq', 'Phone'),
            'email' => Yii::t('faq', 'Email'),
            'cat_id' => Yii::t('faq', 'Category'),
            'question' => Yii::t('faq', 'Question'),
            'answer' => Yii::t('faq', 'Answer'),
            'is_published' => Yii::t('faq', 'Published'),
            'welcome' => Yii::t('faq', 'Welcome'),
            'notification_date' => Yii::t('faq', 'Notify Date'),
            'notification_send' => Yii::t('faq', 'Notify Sended'),
            'order' => Yii::t('faq', 'Order'),
            'show_in_module' => Yii::t('faq', 'Show In Module'),
            'view_count' => Yii::t('faq', 'View Count'),
            'url' => Yii::t('faq', 'Url'),
            'created_at' => Yii::t('faq', 'Created At'),
            'updated_at' => Yii::t('faq', 'Updated At'),
            'validateEmailPhone' => 'validateEmailPhone',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(FaqCategory::className(), ['id' => 'cat_id']);
    }
}
