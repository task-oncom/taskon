<?php

namespace common\modules\testings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use common\modules\testings\models\Question;

class Theme extends \common\components\ActiveRecordModel
{
    const PAGE_SIZE = 10;

	public static function tableName()
	{
		return 'testings_themes';
	}

    public function name()
    {
        return 'Темы';
    }

    public function attributeLabels() 
	{
		return [
			'name' => 'Наименование темы',
			'create_date' => 'Дата создания',
		];
	}

    /**
     * @inheritdoc
     */
	public function rules()
	{
		return [
			[['name'], 'required'],
			[['name'], 'string', 'max' => 200],
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
	            'createdAtAttribute' => 'create_date',
	            'updatedAtAttribute' => null,
	            'value' => new Expression('NOW()'),
	        ],
        ];
    }

    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['theme_id' => 'id']);
    }
}