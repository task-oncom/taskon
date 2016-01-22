<?php

namespace common\modules\testings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

use common\modules\testings\models\TestingPassing;
use common\modules\testings\models\TestingSession;
use common\modules\testings\models\TestingQuestion;

class TestingTest extends \common\components\ActiveRecordModel
{
	const SCENARIO_UPLOAD = 'upload';

    const PAGE_SIZE = 10;

    const TEMP_FOLDER = '/uploads/temp/';

	public $csv_file;
	public $file;

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

	public static function tableName()
	{
		return 'testings_tests';
	}

    public function name()
    {
        return 'Тест';
    }

	public function rules()
	{
		return [
			[['session_id', 'name'], 'required', 'except' => self::SCENARIO_UPLOAD],
			[['minutes', 'questions', 'pass_percent', 'attempt'], 'requiredNotMix', 'except' => self::SCENARIO_UPLOAD],
			[['session_id', 'minutes', 'questions', 'pass_percent', 'attempt', 'mix'], 'integer', 'except' => self::SCENARIO_UPLOAD],
			[['name'], 'string', 'max' => 200, 'except' => self::SCENARIO_UPLOAD],
			[['csv_file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls, xlsx', 'on' => self::SCENARIO_UPLOAD],
			[['mix'], 'safe'],
        ];
	}

	public function requiredNotMix($attribute, $params)
	{ 
	    if($this->$attribute == "" && !$this->mix)
	    {
	      	$this->addError($attribute, 'Не верно указано значение!');
	    }
	}

	public function attributeLabels()
    {
        return [
            'csv_file' => 'Загрузить XLS-файл',
			'attempt' => 'Количество попыток',
			'session_id' => 'Сессия',
			'name' => 'Наименование теста',
			'minutes' => 'Количество минут на прохождение',
			'questions' => 'Количество вопросов в тесте',
			'pass_percent' => 'Лимит прохождения, %',
			'create_date' => 'Время создания',
        ];
    }

    public function getPassings()
    {
        return $this->hasMany(TestingPassing::className(), ['test_id' => 'id']);
    }

    public function getSession()
    {
        return $this->hasOne(TestingSession::className(), ['id' => 'session_id']);
    }

    public function getQuestionsRelation()
    {
        return $this->hasOne(TestingQuestion::className(), ['id' => 'test_id']);
    }

	public function getGammasMG() 
	{
		$cr = new CDbCriteria;
		$cr->with = 'questions';
		$cr->addCondition('questions.test_id = :test_id');
		$cr->addCondition('t.type = :type');
		$cr->group = 't.id';
		$cr->distinct = true;
		$cr->params = array(
			':test_id' => $this->id,
			':type' => TestingGamma::MG,
		);
		$cr->select = 'gamma.*';
		return TestingGamma::model()->findAll($cr);
	}

	public function getGammasTE() 
	{
		$cr = new CDbCriteria;
		$cr->with = 'questions';
		$cr->addCondition('questions.test_id = :test_id');
		$cr->addCondition('t.type = :type');
		$cr->group = 't.id';
		$cr->distinct = true;
		$cr->params = array(
			':test_id' => $this->id,
			':type' => TestingGamma::TE,
		);
		$cr->select = 'gamma.*';
		return TestingGamma::model()->findAll($cr);
	}

	public static function getTestsList($session_id) 
	{
		return ArrayHelper::map(self::find()->where(['session_id' => $session_id]), 'id', 'name');
	}

	private function getPath()
	{
		return Yii::getAlias('@webroot') . self::TEMP_FOLDER;
	}

	public function upload()
    {
        if($this->validate()) 
        {
        	if(!file_exists($this->getPath()))
        	{
        		mkdir($this->getPath(), 0777, true);
        	}

        	$this->file = $this->getPath() . date('dmYHis-') . uniqid() . '.' . $this->csv_file->extension;
            $this->csv_file->saveAs($this->file);
            return true;
        } 
        else 
        {
            return false;
        }
    }

    public function deleteFile()
    {
    	if(file_exists($this->file))
        {
        	unlink($this->file);
        }
    }
}
