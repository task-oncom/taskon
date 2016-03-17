<?php

namespace common\modules\languages\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "languages".
 *
 * @property integer $id
 * @property string $url
 * @property string $local
 * @property string $name
 */
class Languages extends \common\components\ActiveRecordModel
{
    //Переменная, для хранения текущего объекта языка
    static $current = null;

    const DEFAULT_TRUE = 1;
    const DEFAULT_FALSE = 0;

    public static $defaults_title = [
        self::DEFAULT_TRUE => 'Да',
        self::DEFAULT_FALSE => 'Нет',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'languages';
    }
	
	public function name()
	{
		return "Языки";
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'local', 'name'], 'required'],
            [['default'], 'safe'],
            [['url'], 'string', 'max' => 2],
            [['local'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 15],
            [['url'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Идентификатор для отображения в URL',
            'local' => 'Язык пользователя (локаль)',
            'name' => 'Название',
            'default' => 'По умолчанию',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => null,
                'updatedAtAttribute' => null,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) 
        {
            if($this->default)
            {
                static::updateAll(['default' => self::DEFAULT_FALSE], ['default' => self::DEFAULT_TRUE]);
            }

            return true;
        } 
        else 
        {
            return false;
        }
    }

    //Получение текущего объекта языка
    static function getCurrent()
    {
        if(self::$current === null)
        {
            self::$current = self::getDefaultLang();
        }

        return self::$current;
    }

    //Установка текущего объекта языка и локаль пользователя
    static function setCurrent($url = null)
    {
        $language = self::getLangByUrl($url);
        self::$current = ($language === null) ? self::getDefaultLang() : $language;
        Yii::$app->language = self::$current->local;
    }

    //Получения объекта языка по умолчанию
    static function getDefaultLang()
    {
        return static::find()->where('`default` = :default', [':default' => self::DEFAULT_TRUE])->one();
    }

    //Получения объекта языка по буквенному идентификатору
    static function getLangByUrl($url = null)
    {
        if ($url === null) 
        {
            return null;
        } 
        else 
        {
            $language = static::find()->where('url = :url', [':url' => $url])->one();

            if ($language === null) 
            {
                return null;
            }
            else
            {
                return $language;
            }
        }
    }
}
