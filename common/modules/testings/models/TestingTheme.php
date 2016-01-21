<?php

namespace common\modules\testings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class TestingTheme extends \common\components\ActiveRecordModel
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
		];
	}

    /**
     * @inheritdoc
     */
	public function rules()
	{
		return [
			[['name', 'type'], 'required'],
			[['type'], 'integer'],
			[['name'], 'stirng', 'max' => 200],
			// array('id, name, type', 'safe', 'on' => 'search'),
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
        return $this->hasMany(TestingQuestion::className(), ['theme_id' => 'id']);
    }

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('type', $this->type);

		if (Yii::app()->request->getQuery('test')) {
			$criteria->with = 'questions';
			$criteria->together = true;
			$criteria->group = 't.id';
			$criteria->compare('questions.test_id', Yii::app()->request->getQuery('test'));
		}

		return new ActiveDataProvider(get_class($this), array(
			'criteria' => $criteria
		));
	}

	public function questionCount($test_id) {
		$cr = new CDbCriteria;
		$cr->addCondition('test_id = :test_id');
		$cr->addCondition('gamma_id = :gamma_id');
		$cr->params = array(
			'test_id' => $test_id,
			'gamma_id' => $this->id,
		);

		return TestingQuestion::model()->count($cr);
	}

}