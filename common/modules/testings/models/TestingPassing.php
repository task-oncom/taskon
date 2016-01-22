<?php

namespace common\modules\testings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use common\modules\testings\models\TestingUser;

class TestingPassing extends \common\components\ActiveRecordModel
{
    const PAGE_SIZE = 10;

	const FAILED = 0;
	const PASSED = 1;
	const MISTAKE = 2;
	const STARTED = 3;
    const AUTH = 4;
    const NOT_STARTED = 5;

    const PASSING_PERCENT_RIGHT_CENTER = 50;
    const PASSING_PERCENT_RIGHT_ALMOST = 75;
    const PASSING_PERCENT_RIGHT_ALL = 100;

    public static $state_list = [
		self::NOT_STARTED => 'Не сдавал',
        self::FAILED => 'Не сдал',
		self::PASSED => 'Сдал',
		self::MISTAKE => 'Ошибка',
		self::STARTED => 'Начал тестирование',
        self::AUTH => 'Не авторизован',
    ];

    public static $answer_list = [
        self::FAILED => 'Не верно',
		self::PASSED => 'Верно',
    ];

	public static function tableName()
	{
		return 'testings_passings';
	}


    public function name()
    {
        return 'Прохождения';
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

    public function rules()
	{
		return [
			[['user_id', 'test_id'], 'required'],
			[['pass_date', 'pass_date_start', 'attempt'], 'safe'],
			[['test_id'], 'string', 'max' => 11],
			[['end_date'], 'required', 'on' => 'extend'],
			[['user_id', 'is_passed'], 'integer'],
		];
	}

	public function attributeLabels() 
	{
		return [
			'is_passed' => 'Состояние',
			'test_id' => 'Тип теста',
			'user_id' => 'ФИО',
			'create_date' => 'Дата создания',
			'pass_date' => 'Дата прохождения теста',
			'session_id' => 'Сессия',
			'filter_user_email' => 'Email',
			'filter_user_company_name' => 'Наименование компании',
			'filter_user_last_name' => 'ФИО',
		];
	}

	public function getMistake()
    {
        return $this->hasOne(TestingMistake::className(), ['passing_id' => 'id']);
    }

    public function getTest()
    {
        return $this->hasOne(TestingTest::className(), ['id' => 'test_id']);
    }

    public function getUser()
    {
        return $this->hasOne(TestingUser::className(), ['id' => 'user_id']);
    }

    public function getQuestions()
    {
        return $this->hasMany(TestingQuestionPassing::className(), ['passing_id' => 'id']);
    }

    public function getCountPassedQuestions() 
    {
        $count = 0;

        foreach($this->questions as $question) 
        {
            if($question->isRight) 
            {
                $count++;
            }
        }

        return $count;
    }

	

	public function buildSearchCriteria() {

		$criteria = new CDbCriteria();

		$criteria->compare('id', $this->id);
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('test_id', $this->test_id);
		//$criteria->compare('is_passed', $this->is_passed);
		$criteria->compare('create_date', $this->create_date, true);

		$tpassing = Yii::app()->request->getQuery('TestingPassing');

		$pass_date = 'CONCAT( RIGHT( LEFT( pass_date, 10 ) , 4 ) ,  "-", TRIM( 
TRAILING CONCAT(  ".", SUBSTRING_INDEX( LEFT( pass_date, 10 ) ,  ".", -1 ) ) 
FROM TRIM( 
LEADING CONCAT( SUBSTRING_INDEX( LEFT( pass_date, 10 ) ,  ".", 1 ) ,  "." ) 
FROM LEFT( pass_date, 10 ) ) ) ,  "-", LEFT( pass_date, 2 ) )';

		if (($tpassing['pass_date']) && (Yii::app()->request->getQuery('date_to'))) {

			$criteria->addCondition($pass_date.' BETWEEN "'.$tpassing['pass_date'].'" AND "'.Yii::app()->request->getQuery('date_to').'"');
		}
		elseif(Yii::app()->request->getQuery('date_to')) {
			$criteria->addCondition($pass_date.' < "'.Yii::app()->request->getQuery('date_to').'"');
		}
		elseif($tpassing['pass_date']) {
			$criteria->addCondition($pass_date.' > "'.Yii::app()->request->getQuery('pass_date').'"');
		}

		if (Yii::app()->request->getQuery('status_is_null')) {
			$criteria->addCondition('is_passed is null');
		}

		$criteria->with = array('user','test','mistake');

		$criteria->order = 'user.last_name, user.first_name, user.patronymic, test.name';
		$criteria->together = true;

        if ($this->is_passed == 4) {
            $criteria->addCondition('user.create_date > "2014-06-05 00:00:00"');
            $criteria->addCondition('user.is_auth = 0');
        } elseif ($this->is_passed == 3) {
            $criteria->addCondition('is_passed = 0');
            $criteria->addCondition('pass_date is null');
            $criteria->addCondition('mistake.passing_id is null');
        } elseif ($this->is_passed == 2) {
            $criteria->addCondition('mistake.passing_id = t.id');
        } elseif ($this->is_passed == 1) {
            $criteria->addCondition('is_passed = 1');
            $criteria->addCondition('mistake.passing_id is null');
        } elseif ($this->is_passed === '0') {
            $criteria->addCondition('is_passed = 0');
            $criteria->addCondition('pass_date is not null');
            $criteria->addCondition('mistake.passing_id is null');
        } elseif ($this->is_passed == 5) {
            $criteria->addCondition('is_passed is null');
            $criteria->addCondition('mistake.passing_id is null');
        }

		$criteria->compare('test.session_id', Yii::app()->request->getQuery('session'));

		if (isset($_GET['email']) && $_GET['email'])
        {
			$criteria->compare('user.email', $_GET['email'], true);
			$criteria->together = true;
        }

		if (isset($_GET['company_name']) && $_GET['company_name'])
        {
			$criteria->compare('user.company_name', $_GET['company_name'], true);
			$criteria->together = true;
        }

		if (isset($_GET['fio']) && $_GET['fio'])
        {
			$criteria->compare('user.last_name', $_GET['fio'], true);
			$criteria->together = true;
		}

        if (Yii::app()->user->getRole() == 'schneider_electric')
        {
            $criteria->compare('user.tki', Yii::app()->user->getName(), true);
            $criteria->together = true;
        }

		return $criteria;
	}

	public function getGammas() {
		$cr = new CDbCriteria;
		$cr->with = 'questions.passings.passing';
		$cr->addCondition('passing.id = :passing_id');
		$cr->group = 't.id';
		$cr->distinct = true;
		$cr->params = array(
			':passing_id' => $this->id,
		);
		$cr->select = 'gamma.*';
		return TestingGamma::model()->findAll($cr);
	}

	public function gammaPercent($gamma_id) {
		$all = 0;
		$right = 0;
		foreach ($this->questions as $pq) {
			if ($pq->question->gamma_id == $gamma_id) {
				$all++;
				if ($pq->isRight) {
					$right++;
				}
			}
		}
		return ($all <> 0) ? ceil($right*100/$all) : "- ";
	}

	public function getPercent() {
		$all = 0;
		$right = 0;
		foreach ($this->questions as $pq) {
			$all++;
			if ($pq->isRight) {
				$right++;
			}
		}
		return ($all <> 0) ? ceil($right*100/$all) : "- ";
	}

	public function getPercentStep() 
	{
		return ceil(($this->percent * 100) / $this->test->pass_percent);
	}

	public function getMessageStep()
	{
		$list = array(
			self::PASSING_PERCENT_RIGHT_ALL => Setting::getValue('message_passing_percent_right_all'),
	        self::PASSING_PERCENT_RIGHT_ALMOST => Setting::getValue('message_passing_percent_right_almost'),
			self::PASSING_PERCENT_RIGHT_CENTER => Setting::getValue('message_passing_percent_right_center'),
	    );

	    foreach ($list as $percent => $message) 
	    {
	    	if($this->percentStep >= $percent)
		    {
		    	return array('message' => $list[$percent], 'percent' => $percent);
		    }
	    }
	    
	    return;
	}

	public function getTime() {
		if($this->pass_date_start && $this->pass_date)
		{
			return (strtotime($this->pass_date) - strtotime($this->pass_date_start));
		}
		else
		{
			$timeSummary = 0;
			foreach ($this->questions as $q) {
				$timeSummary += $q->answer_time;
			}
			return $timeSummary;
		}
	}

	public function recountPassResult() {
		if ($this->percent >= $this->test->pass_percent) {
			$this->is_passed = self::PASSED;
		} else {
			$this->is_passed = self::FAILED;
		}
		return true;
	}

	public function getStatus() 
	{
        $user = TestingUser::findOne($this->user_id);

        if ((strtotime($user->create_date) > mktime(0,0,0,6,5,2014)) && ($user->is_auth == 0)) 
        {
            return self::AUTH;
        } 
        elseif ($this->mistake) 
        {
			return self::MISTAKE;
		} 
		elseif (($this->is_passed === '0') && ($this->pass_date === null) && $this->attempt < $this->test->attempt && strtotime($this->pass_date_start) + ($this->test->minutes * 60) >= time()) 
		{
			return self::STARTED;
        } 
        elseif($this->is_passed === 0 && ($this->attempt > $this->test->attempt || strtotime($this->pass_date_start) + ($this->test->minutes * 60) >= time()))
        {
        	return self::FAILED;
        }
        elseif ($this->is_passed === NULL) 
        {
            return self::NOT_STARTED;
        }
        else 
        {
			return $this->is_passed;
		}
	}

	public function sendNotAttempt($message)
	{
		$body    = Setting::getValue('email_not_attempt_body');
		$subject = 'Тестирование - обращение ' . uniqid();

		$test = $this->test;
		$session = $test->session;
		$user = $this->user;

		$mailer_letter = MailerLetter::model();
		$body          = $mailer_letter->compileText($body, array(
			'fio_user_link' => CHtml::link($user->fio, Yii::app()->request->hostInfo . Yii::app()->urlManager->createUrl('/testings/testingUserAdmin/view', array('id' => $this->user_id))),
			'session_name' => $session->name,
			'test_name' => $test->name,
			'message' => $message,
			'session_users_link' => CHtml::link('пользователи', Yii::app()->request->hostInfo . Yii::app()->urlManager->createUrl('/testings/testingUserAdmin/manage', array('session' => $session->id))),
			'attempt_link' => CHtml::link('переназначить', Yii::app()->request->hostInfo . Yii::app()->urlManager->createUrl('/testings/TestingPassingAdmin/reAttempt', array('id' => $this->id))),
		));
		unset($mailer_letter);

      	return MailerModule::sendMailUniSender(Setting::getValue('not_attempt_email'), $subject, $body);
	}

	public static function declOfNum($number, $titles)
	{
	    $cases = array (2, 0, 1, 1, 1, 2);
	    return $number." ".$titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
	}
}
