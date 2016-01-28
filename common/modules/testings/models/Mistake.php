<?php

namespace common\modules\testings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Mistake extends \common\components\ActiveRecordModel
{
    const PAGE_SIZE = 10;

	const NOT_AGREED = 0;
	const AGREED = 1;

	public $company;
	public $managerField;
	public $mistakeField;
	public $retest = 0;

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
        return 'Сообщения об ошибках';
    }

    public function attributeLabels() 
	{
		return [
			'passing_id' => 'Прохождение',
			'description' => 'Описание ошибки',
			'is_expert_agreed' => 'Согласовано ли с экспертом',
			'create_date' => 'Время создания',
		];
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
			[['retest'], 'safe']
        ];
	}

	public function getPassing()
    {
        return $this->hasOne(Passing::className(), ['id' => 'passing_id']);
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
}