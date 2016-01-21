<?php

namespace common\modules\testings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use common\modules\testings\models\TestingAnswer;
use common\modules\testings\models\TestingTheme;
use common\modules\testings\models\TestingTest;
use common\modules\testings\models\TestingQuestionPassing;

class TestingQuestion extends \common\components\ActiveRecordModel
{
    const PAGE_SIZE = 10;

	const DELIMITER = ';';

	const ONE_OPTION = 1;
	const FEW_OPTIONS = 2;
	const USER_ANSWER = 3;

	const ACTIVE = 1;
	const HIDDEN = 0;

    public static $type_list = [
        self::ONE_OPTION => 'Нужно выбрать один вариант',
        self::FEW_OPTIONS => 'Нужно выбрать несколько вариантов',
		self::USER_ANSWER => 'Нужно ввести свой вариант ответа',
    ];

    public static $active_list = [
        self::ACTIVE => 'Активен',
        self::HIDDEN => 'Скрыт',
    ];

	public static function tableName()
	{
		return 'testings_questions';
	}

    public function name()
    {
        return 'Вопросы';
    }

	public function attributeLabels() 
	{
		return [
			'is_active' => 'Активен/скрыт',
			'author' => 'Автор вопроса',
			'test_id' => 'Тест',
			'theme_id' => 'Тема',
			'text' => 'Текст вопроса',
			'type' => 'Тип вопроса',
			'create_date' => 'Время создания',
		];
	}

	/**
     * @inheritdoc
     */
	public function rules()
	{
		return [
			[['theme_id', 'test_id', 'text', 'is_active', 'type'], 'required'],
			[['theme_id', 'test_id', 'type', 'is_active'], 'integer'],
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

    public function getAnswers()
    {
        return $this->hasMany(TestingAnswer::className(), ['question_id' => 'id']);
    }

    public function getTheme()
    {
        return $this->hasOne(TestingTheme::className(), ['id' => 'theme_id']);
    }

    public function getTest()
    {
        return $this->hasOne(TestingTest::className(), ['id' => 'test_id']);
    }

    public function getPassings()
    {
        return $this->hasMany(TestingQuestionPassing::className(), ['question_id' => 'id']);
    }

    public function getFiles()
    {
        return $this->hasMany(FileManager::className(), ['object_id' => 'id'])
        	->andWhere([FileManager::tableName() . '.model_id' => get_class($this)])
        	->andWhere([FileManager::tableName() . '.tag' => 'files'])
        	->orderBy(FileManager::tableName() . '.order DESC');
    }

	public function getRightAnswer() 
	{
		switch ($this->type) {
			case self::ONE_OPTION :
				$answerModel = TestingAnswer::model()->find('question_id = :question and is_right = 1',array(':question' => $this->id));
				if ($answerModel) {
					$answer = trim(preg_replace('/\s+/', ' ', $answerModel->text));
				} else {
					$answer = null;
				}
				break;
			case self::FEW_OPTIONS :
				$answerModel = TestingAnswer::model()->findAll('question_id = :question and is_right = 1',array(':question' => $this->id));
				if ($answerModel) {
					$arr = array();
					foreach ($answerModel as $answerItem) {
						$arr[] = trim(preg_replace('/\s+/', ' ', $answerItem->text));
					}
					sort($arr);
					$answer = implode(self::DELIMITER,$arr);
				} else {
					$answer = null;
				}
				break;
			case self::USER_ANSWER :
				$answerModel = TestingAnswer::model()->find('question_id = :question',array(':question' => $this->id));
				if ($answerModel) {
					$answer = trim(preg_replace('/\s+/', ' ', $answerModel->text));
				} else {
					$answer = null;
				}
				break;
		}
		return $answer;
	}
	
	public static function getThemesList() 
	{
		return \yii\helpers\ArrayHelper::map(TestingTheme::find()->all(), 'id', 'name');
	}
}
