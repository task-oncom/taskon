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
    const PAGE_SIZE = 10;

	public $csv_file;

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
			[['session_id', 'name'], 'required'],
			[['minutes', 'questions', 'pass_percent', 'attempt'], 'requiredNotMix'],
			[['session_id', 'minutes', 'questions', 'pass_percent', 'attempt', 'mix'], 'integer'],
			[['name'], 'string', 'max' => 200],
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
            'csv_file' => 'Загрузить csv-файл',
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
}
