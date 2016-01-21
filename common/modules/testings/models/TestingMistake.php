<?php

namespace common\modules\testings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class TestingMistake extends \common\components\ActiveRecordModel
{
    const PAGE_SIZE = 10;

	const NOT_AGREED = 0;
	const AGREED = 1;

	public $company;
	public $managerField;
	public $mistakeField;

    public static $state_list = [
        self::NOT_AGREED => 'Не согласовано',
		self::AGREED => 'Согласовано',
    ];

	public static function tableName()
	{
		return 'testings_mistakes';
	}

    public function name()
    {
        return 'Переназначения';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
    	// $behaviors['FileManager'] = array(
     //        'class' => 'application.components.activeRecordBehaviors.FileManagerBehavior'
     //    );
        return [
            [
	            'class' => TimestampBehavior::className(),
	            'createdAtAttribute' => 'create_date',
	            'updatedAtAttribute' => 'create_date',
	            'value' => new Expression('NOW()'),
	        ],
        ];
    }

    public function rules()
	{
		return [
			[['passing_id', 'description', 'is_expert_agreed'], 'required'],
			[['passing_id', 'is_expert_agreed'], 'integer'],
			[['description'], 'string', 'max' => 3000],
			// array('id, passing_id, description, is_expert_agreed, create_date', 'safe', 'on' => 'search'),
        ];
	}

	public function getPassing()
    {
        return $this->hasOne(TestingPassing::className(), ['id' => 'passing_id']);
    }

    // public function getFiles()
    // {
    //     return $this->hasMany(FileManager::className(), ['object_id' => 'id'])
    //     	->andWhere([
	   //      	'files.model_id' => get_class($this),
	   //      	'files.tag' => 'files'
	   //      ])
	   //      ->orderBy(['files.order DESC']);
    // }

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('id', $this->id);
		$criteria->compare('passing_id', $this->passing_id);
		$criteria->compare('description', $this->description, true);
		$criteria->compare('is_expert_agreed', $this->is_expert_agreed);
		$criteria->compare('create_date', $this->create_date, true);

		return new ActiveDataProvider(get_class($this), array(
			'criteria' => $criteria
		));
	}
}