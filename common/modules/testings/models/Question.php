<?php

namespace common\modules\testings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use common\modules\testings\models\Answer;
use common\modules\testings\models\Theme;
use common\modules\testings\models\Test;
use common\modules\testings\models\QuestionPassing;

class Question extends \common\components\ActiveRecordModel
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
        return $this->hasMany(Answer::className(), ['question_id' => 'id']);
    }

    public function getTheme()
    {
        return $this->hasOne(Theme::className(), ['id' => 'theme_id']);
    }

    public function getTest()
    {
        return $this->hasOne(Test::className(), ['id' => 'test_id']);
    }

    public function getPassings()
    {
        return $this->hasMany(QuestionPassing::className(), ['question_id' => 'id']);
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
		$query = Answer::find()->where(['question_id' => $this->id]);

		switch ($this->type) 
		{
			case self::ONE_OPTION:
				$model = $query->andWhere(['is_right' => 1])->one();

				if($model)
				{
					$answer = trim(preg_replace('/\s+/', ' ', $model->text));
				} 
				else 
				{
					$answer = null;
				}
				break;

			case self::FEW_OPTIONS:
				$models = $query->andWhere(['is_right' => 1])->all();

				if ($models) 
				{
					$arr = array();

					foreach ($models as $model)
					{
						$arr[] = trim(preg_replace('/\s+/', ' ', $model->text));
					}

					sort($arr);
					$answer = implode(self::DELIMITER, $arr);
				} 
				else 
				{
					$answer = null;
				}
				break;

			case self::USER_ANSWER:
				$models = $query->one();
				if ($model) 
				{
					$answer = trim(preg_replace('/\s+/', ' ', $model->text));
				} 
				else 
				{
					$answer = null;
				}
				break;
		}
		return $answer;
	}
	
	public static function getThemesList() 
	{
		return \yii\helpers\ArrayHelper::map(Theme::find()->all(), 'id', 'name');
	}
}
