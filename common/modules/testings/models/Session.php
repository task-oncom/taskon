<?php

namespace common\modules\testings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use common\modules\testings\models\Test;
use common\modules\testings\models\User;
use common\modules\testings\models\Passing;
use common\modules\testings\models\UserGroup;

class Session extends \common\components\ActiveRecordModel
{
    const PAGE_SIZE = 10;

	public $csv_file;
	public $file;

	const TEMP_FOLDER = '/uploads/temp/';

	const SCENARIO_UPLOAD = 'upload';

	const IS_PRESENT = 0;
	const IS_PAST = 1;
	const IS_FUTURE = 2;

    public static $state_list = [
        self::IS_PRESENT => 'Сессия в данный момент активна',
		self::IS_PAST => 'Сессия закончилась',
		self::IS_FUTURE => 'Сессия ещё не начиналась',
    ];

	public static function tableName()
	{
		return 'testings_sessions';
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

    public function name()
    {
        return 'Сессия';
    }

	public function rules()
	{
		return [
			[['name', 'start_date', 'end_date'], 'required', 'except' => self::SCENARIO_UPLOAD],
			[['name'], 'string', 'max' => 80, 'except' => self::SCENARIO_UPLOAD],
			[['csv_file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls, xlsx', 'on' => self::SCENARIO_UPLOAD],
		];
	}

	public function getTests()
    {
        return $this->hasMany(Test::className(), ['session_id' => 'id']);
    }

    public function getGroups()
    {
        return $this->hasMany(UserGroup::className(), ['session_id' => 'id']);
    }

	public function attributeLabels() 
	{
		return [
			'name' => 'Наименование сессии',
			'start_date' => 'Дата начала',
			'end_date' => 'Дата окончания',
			'create_date' => 'Дата создания сессии',
			'csv_file' => 'Загрузить csv-файл',
		];
	}

	public function getUsersOverall() 
	{
		$query = Passing::find()->joinWith('test');

		$query->andWhere([
            Test::tableName() . '.session_id' => $this->id,
        ]);

        $query->groupBy(['user_id', 'test_id']);

		return $query->count();
	}

	public function getUsersPassed() 
	{
		$query = Passing::find()->joinWith('test');

		$query->andWhere([
            Test::tableName() . '.session_id' => $this->id,
            'is_passed' => Passing::PASSED,
        ]);

        $query->groupBy(['user_id', 'test_id']);

		return $query->count();
	}

	public function getUsersFailed() 
	{
		return $this->usersOverAll - $this->usersPassed;
	}

	public function getCurrentState() 
	{
		$start_date = strtotime($this->start_date);
		$end_date = strtotime($this->end_date);
		$now = strtotime(date('d.m.Y H:i'));

		$result = null;

		if ($start_date > $now) 
		{
			$result = self::IS_FUTURE;
		}

		if ($end_date <= $now) 
		{
			$result = self::IS_PAST;
		}

		if (($start_date <= $now) && ($end_date > $now)) 
		{
			$result = self::IS_PRESENT;
		}

		return $result;
	}

	public function getUsers() 
	{
		$query = User::find()->joinWith('passings.test');

		$query->andWhere([
             Test::tableName() . '.session_id' => $this->id,
        ]);

        $query->groupBy(['t.id']);

		return $query->all();
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

	public function sendMailByList($users)
	{
		for ($i = 0; $i < count($users); $i++)
		{
			$dublicates = array($users[$i]);
			$email = $users[$i]->email;
			$users[$i] = null;

			for ($j = 0; $j < count($users); $j++)
			{
				if($users[$j] && $email == $users[$j]->email)
				{
					array_push($dublicates, $users[$j]);
					$users[$j] = null;
				}
			}
			
			if(isset($dublicates[0]))
			{
				$this->sendMessage($dublicates);
			}
		}
	}

	private function sendMessage($users)
	{
		if(!is_array($users) || count($users) == 1)
		{
			if(is_array($users))
			{
				$model = $users[0];
			}

			$tests = $model->assignedTestsForSession($this->id);

			if ($tests)
			{
				if (count($tests) > 1)
				{
					$this->sendEmailToUser_MultipleTest($model, $tests, $this->id);
				} 
				else 
				{
					$this->sendEmailToUser_SingleTest($model, $tests, $this->id);
				}
			}
			unset($tests);
		}
		else
		{
			$this->sendEmailToUser_MultipleUser($users, $this->id);
		}
	}

	public function sendEmailToUser_MultipleTest($user, $tests, $session_id, $notified = false) 
	{
		if($notified)
		{
			$body    = Setting::getValue('email_testing_passing_tracking_body');
			$subject = Setting::getValue('email_testing_passing_tracking_subject');
		}
		else
		{
			$body    = Setting::getValue('email_multiple_test_notice_body');
			$subject = Setting::getValue('email_test_notice_head');
		}
		
		$test_list = '<ul>';
		$test_time_list = '<ul>';
		$test_pass_limit_list = '<ul>';

		foreach ($tests as $test) 
		{
			if($test->name != '.') $test_list .= '<li>"'.$test->name.'"</li>';
			$test_time_list .= '<li>"'.$test->name.'" - не более '.$test->minutes.' минут</li>';
			$test_pass_limit_list .= '<li>"'.$test->name.'" - '.$test->pass_percent.'% правильных ответов от предложенных вопросов</li>';
		}

		$test_list .= '</ul>';
		$test_time_list .= '</ul>';
		$test_pass_limit_list .= '</ul>';

		$history = TestingSendHistory::model()->findByAttributes(array('session_id' => $session_id, 'user_id' => $user->id));
		if(!$history)
		{
			$history = new TestingSendHistory();
		}
		
		$history->session_id = $session_id;
		$history->email = $user->email;
		$history->user_id = $user->id;
		$history->unisender_status = TestingSendHistory::STATUS_NOT_SENT;
		$history->save();

		$mailer_letter = MailerLetter::model();
		$body          = $mailer_letter->compileText($body, array(
			'polite'    => ($user->sex == TestingUser::MAN) ? 'уважаемый' : 'уважаемая',
			'user'      => $user,
			'password'  => $user->password,
			'test_list' => $test_list,
			'test_time_list' => $test_time_list,
			'test_pass_limit_list' => $test_pass_limit_list,
			'session'   => TestingSession::model()->findByPk($session_id),
			'test_info_link' => CHtml::link(Yii::app()->request->hostInfo . Yii::app()->urlManager->createUrl('/testings/testingTest'), Yii::app()->request->hostInfo . Yii::app()->urlManager->createUrl('/testings/testingTest')),
			'test_link' => CHtml::link(Yii::app()->request->hostInfo . Yii::app()->urlManager->createUrl('/testings/testingTest'), Yii::app()->request->hostInfo . Yii::app()->urlManager->createUrl('/testings/testingTest')),
		));
		unset($mailer_letter);

      	MailerModule::sendMailUniSender($user->email, $subject, $body, $history);
	}

	public function sendEmailToUser_SingleTest($user, $tests, $session_id, $notified = false)
	{
		if($notified)
		{
			$body    = Setting::getValue('email_testing_passing_tracking_body');
			$subject = Setting::getValue('email_testing_passing_tracking_subject');
		}
		else
		{
			$body    = Setting::getValue('email_single_test_notice_body');
			$subject = Setting::getValue('email_test_notice_head');
		}

		$test_list = '';
		$test_time_list = '';
		$test_pass_limit_list = '';

		foreach ($tests as $test) 
		{
			$test_list = $test->name;
			$test_time_list = $test->minutes;
			$test_pass_limit_list = $test->pass_percent;
		}

		$history = TestingSendHistory::model()->findByAttributes(array('session_id' => $session_id, 'user_id' => $user->id));
		if(!$history)
		{
			$history = new TestingSendHistory();
		}

		$history->session_id = $session_id;
		$history->user_id = $user->id;
		$history->email = $user->email;
		$history->unisender_status = TestingSendHistory::STATUS_NOT_SENT;
		$history->save();

		$mailer_letter = MailerLetter::model();
		$body          = $mailer_letter->compileText($body, array(
			'polite'    => ($user->sex == TestingUser::MAN) ? 'уважаемый' : 'уважаемая',
			'user'      => $user,
			'password'  => $user->password,
			'test_list' => $test_list,
			'test_time_list' => $test_time_list,
			'test_pass_limit_list' => $test_pass_limit_list,
			'session'   => TestingSession::model()->findByPk($session_id),
			'test_info_link' => CHtml::link(Yii::app()->request->hostInfo . Yii::app()->urlManager->createUrl('/testings/testingTest'),Yii::app()->request->hostInfo . Yii::app()->urlManager->createUrl('/testings/testingTest')),
			'test_link' => CHtml::link(Yii::app()->request->hostInfo . Yii::app()->urlManager->createUrl('/testings/testingTest'),Yii::app()->request->hostInfo . Yii::app()->urlManager->createUrl('/testings/testingTest')),
		));
		unset($mailer_letter);

      	MailerModule::sendMailUniSender($user->email, $subject, $body, $history);
	}

	private function sendEmailToUser_MultipleUser($users, $session_id)
	{
        foreach($users as $user) 
        {
            $body    = Setting::getValue('email_multiple_user_test_notice_body');
            $subject = Setting::getValue('email_test_notice_head');

            $history = TestingSendHistory::model()->findByAttributes(array('session_id' => $session_id, 'user_id' => $user->id));
            if(!$history)
            {
                $history = new TestingSendHistory();
            }
            $filename = $history->generateFile($users, $session_id);

            $history->session_id = $session_id;
            $history->email = $user->email;
            $history->user_id = $user->id;
            $history->unisender_status = TestingSendHistory::STATUS_NOT_SENT;
            $history->save();

            $attachments = array(
                $filename => $history->getFilePath()
            );

            $mailer_letter = MailerLetter::model();
            $body          = $mailer_letter->compileText($body, array(
                'test_link' => CHtml::link(Yii::app()->controller->createAbsoluteUrl('/testings/testingTest'), Yii::app()->controller->createAbsoluteUrl('/testings/testingTest')),
            ));
            unset($mailer_letter);

            MailerModule::sendMailUniSender($users[0]->email, $subject, $body, $history, $attachments);
        }
	}

}