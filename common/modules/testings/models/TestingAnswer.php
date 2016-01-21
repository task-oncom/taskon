<?php

namespace common\modules\testings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use common\modules\testings\models\TestingQuestion;

class TestingAnswer extends \common\components\ActiveRecordModel
{
    const PAGE_SIZE = 10;

	const IS_NOT_RIGHT = 0;
	const IS_RIGHT = 1;

    public static $type_list = [
        self::IS_NOT_RIGHT => 'Нет',
        self::IS_RIGHT => 'Да',
    ];

	public static function tableName()
	{
		return 'testings_answers';
	}

    public function name()
    {
        return 'Ответы';
    }

	public function attributeLabels() 
	{
		return [
			'text' => 'Текст ответа',
			'question_id' => 'Вопрос',
			'is_right' => 'Верный ответ',
			'create_date' => 'Время создания',
		];
	}

	public function rules()
	{
		return [
			[['question_id', 'text', 'is_right'], 'required'],
			[['question_id', 'is_right'], 'integer'],
			// array('id, question_id, text, is_right', 'safe', 'on' => 'search'),
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

    public function getQuestion()
    {
        return $this->hasOne(TestingQuestion::className(), ['id' => 'question_id']);
    }
}