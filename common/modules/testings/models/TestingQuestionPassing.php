<?php

class TestingQuestionPassing extends ActiveRecordModel
{
    const PAGE_SIZE = 10;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return 'testings_questions_passings';
	}


    public function name()
    {
        return 'Модель TestingsQuestionsPassings';
    }

	public function behaviors(){
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'create_date',
				'updateAttribute' => null,
			)
		);
	}

	public function rules()
	{
		return array(
			array('passing_id, question_id', 'required'),
			array('passing_id, answer_time', 'numerical', 'integerOnly' => true),
			array('question_id', 'length', 'max' => 11),
			array('user_answer', 'length', 'max' => 3000),

			array('id, passing_id, question_id, user_answer, answer_time', 'safe', 'on' => 'search'),
		);
	}

	public function attributeLabels() {
		return array(
			'question_id' => 'Наименование вопроса',
			'user_answer' => 'Ответ пользователя',
			'answer_time' => 'Время прохождения, секунд',
			'create_date' => 'Дата создания',
			'question_text' => 'Текст вопроса',
			'answer_text' => 'Правильный ответ',
		);
	}

	public function relations()
	{
		return array(
			'question' => array(self::BELONGS_TO, 'TestingQuestion', 'question_id'),
			'passing' => array(self::BELONGS_TO, 'TestingPassing', 'passing_id'),
		);
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

	public function getIsRight() {
		$right = false;
		switch ($this->question->type) {
			case TestingQuestion::ONE_OPTION :
				$right = (trim($this->answer_text) == trim($this->user_answer));
				break;
			case TestingQuestion::FEW_OPTIONS :
				$arr = explode(TestingQuestion::DELIMITER, $this->answer_text);
				foreach ($arr as $index => $item) {
					$arr[$index] = trim(preg_replace('/\s+/', ' ', mb_strtolower($item)));
				}
				$arr2 = explode(TestingQuestion::DELIMITER, $this->user_answer);
				foreach ($arr2 as $index => $item) {
					$arr2[$index] = trim(preg_replace('/\s+/', ' ', mb_strtolower($item)));
				}
				sort($arr);
				sort($arr2);
				$right = ($arr == $arr2); // TRUE в случае, если $a и $b содержат одни и те же элементы
				break;
			case TestingQuestion::USER_ANSWER :
				$opt1 = str_replace(';', ',', preg_replace('/\s+/', '', mb_strtolower($this->answer_text)));
				$opt2 = str_replace(';', ',', preg_replace('/\s+/', '', mb_strtolower($this->user_answer)));
				$right = (trim($opt1) == trim($opt2));
				break;
		}
		return $right;
	}

}