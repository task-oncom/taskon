<?php

namespace common\modules\testings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class TestingUserGroup extends \common\components\ActiveRecordModel
{
    const PAGE_SIZE = 10;

	/**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
	            'class' => TimestampBehavior::className(),
	            'createdAtAttribute' => 'created',
	            'updatedAtAttribute' => null,
	            'value' => new Expression('NOW()'),
	        ],
        ];
    }

	public static function tableName()
	{
		return 'testings_users_groups';
	}

    public function name()
    {
        return 'Группы пользователей';
    }

	public function attributeLabels() 
	{
		return [
			'name' => 'Название группы',
            'created' => 'Дата импорта',
            'session_id' => 'Сессия',
		];
	}

	public function rules()
	{
		return [
			[['name'], 'required'],
			[['created', 'session_id'], 'safe'],
		];
	}
}
