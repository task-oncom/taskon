<?php

namespace common\modules\testings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class TestingUserGroupAssign extends \common\components\ActiveRecordModel
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
		return 'testings_users_groups_assign';
	}

    public function name()
    {
        return 'Связка пользователей и групп';
    }

	public function attributeLabels() 
	{
		return [
			'group_id' => 'Группа',
            'user_id' => 'Пользователь',
            'session_id' => 'Сессия',
		];
	}

	public function rules()
	{
		return [
			[['group_id', 'user_id', 'session_id'], 'required'],
		];
	}

	public function getGroup()
    {
        return $this->hasOne(TestingUserGroup::className(), ['id' => 'group_id']);
    }

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name);
		$criteria->compare('session_id', $this->session_id);
		$criteria->compare('created', $this->created);

		$criteria->order = 't.id DESC';

		return new ActiveDataProvider(get_class($this), array(
			'criteria' => $criteria
		));
	}
}
