<?php

namespace common\modules\testings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use common\modules\testings\models\Question;
use common\modules\testings\models\Passing;

class QuestionPassing extends \common\components\ActiveRecordModel
{
    const PAGE_SIZE = 10;

	public static function tableName()
	{
		return 'testings_questions_passings';
	}


    public function name()
    {
        return 'Назначенные вопросы';
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

	/**
     * @inheritdoc
     */
	public function rules()
	{
		return [
			[['passing_id', 'question_id'], 'required'],
			[['passing_id', 'answer_time'], 'integer'],
			[['question_id'], 'string', 'max' => 11],
			[['user_answer'], 'string', 'max' => 3000],
			// array('id, passing_id, question_id, user_answer, answer_time', 'safe', 'on' => 'search'),
        ];
	}

	public function attributeLabels() 
	{
		return [
			'question_id' => 'Наименование вопроса',
			'user_answer' => 'Ответ пользователя',
			'answer_time' => 'Время прохождения, секунд',
			'create_date' => 'Дата создания',
			'question_text' => 'Текст вопроса',
			'answer_text' => 'Правильный ответ',
		];
	}

	public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }

    public function getPassing()
    {
        return $this->hasOne(Passing::className(), ['passing_id' => 'id']);
    }

	public function search($passing = null)
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('passing_id', $this->passing_id);
		$criteria->compare('question_id', $this->question_id, true);
		$criteria->compare('user_answer', $this->user_answer, true);
		$criteria->compare('answer_time', $this->answer_time);

		if ($passing) {
			$criteria->addCondition('passing_id = :passing');
			$criteria->params = array(':passing'=>$passing);
		}

		return new ActiveDataProvider(get_class($this), array(
			'criteria' => $criteria
		));
	}

	public function getIsRight() 
	{
		$right = false;

		switch ($this->question->type) 
		{
			case Question::ONE_OPTION:
				$right = (trim($this->answer_text) == trim($this->user_answer));
				break;

			case Question::FEW_OPTIONS:
				$arr = explode(Question::DELIMITER, $this->answer_text);
				foreach ($arr as $index => $item) 
				{
					$arr[$index] = trim(preg_replace('/\s+/', ' ', mb_strtolower($item)));
				}
				$arr2 = explode(Question::DELIMITER, $this->user_answer);
				foreach ($arr2 as $index => $item) 
				{
					$arr2[$index] = trim(preg_replace('/\s+/', ' ', mb_strtolower($item)));
				}
				sort($arr);
				sort($arr2);
				$right = ($arr == $arr2); // TRUE в случае, если $a и $b содержат одни и те же элементы
				break;

			case Question::USER_ANSWER:
				$opt1 = str_replace(';', ',', preg_replace('/\s+/', '', mb_strtolower($this->answer_text)));
				$opt2 = str_replace(';', ',', preg_replace('/\s+/', '', mb_strtolower($this->user_answer)));
				$right = (trim($opt1) == trim($opt2));
				break;
		}

		return $right;
	}

}