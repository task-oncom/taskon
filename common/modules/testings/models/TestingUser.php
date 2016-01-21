<?php

namespace common\modules\testings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use common\modules\testings\models\TestingPassing;
use common\modules\testings\models\TestingUserGroupAssign;
use common\modules\testings\models\TestingSendHistory;
use common\modules\users\models\User;

class TestingUser extends \common\components\ActiveRecordModel
{
    const PAGE_SIZE = 10;

	const WOMAN = 0;
	const MAN = 1;

    public static $sex_list = [
        self::WOMAN => 'Женский',
        self::MAN => 'Мужской',
    ];

	public static function tableName()
	{
		return 'testings_users';
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
        return 'Пользователи';
    }

	public function attributeLabels() 
	{
		return [
			'company_name' => 'Компания',
            'is_auth' => 'sd',
            'filter_group_id' => 'Группа',
            'sex' => 'Пол',
            'first_name' => 'Имя',
            'patronymic' => 'Отчество',
            'last_name' => 'Фамилия',
            'company_name' => 'Наименование компании',
            'city' => 'Город',
            'login' => 'Логин',
            'password' => 'Пароль',
            'email' => 'Email',
            'manager_id' => 'Ответственный менеджер',
            'tki' => 'ТКИ',
            'region' => 'Регион',
            'create_date' => 'Время создания',
		];
	}

	public function rules()
	{
		return [
			[['sex', 'first_name', 'last_name', 'company_name', 'email', 'manager_id'], 'required'],
			[['sex', 'is_auth'], 'integer'],
			[['first_name', 'patronymic', 'last_name'], 'string', 'max' => 50],
			[['company_name'], 'string', 'max' => 250],
			[['email'], 'string', 'max' => 150],
			[['tki', 'city', 'region'], 'string', 'max' => 100],
			[['manager_id'], 'string', 'max' => 11],

			[['pass_date', 'pass_date_start', 'attempt'], 'safe'],
			[['end_date'], 'required', 'on' => 'extend'],
		];
	}

	public function getPassings()
    {
        return $this->hasMany(TestingPassing::className(), ['user_id' => 'id']);
    }

    public function getManager()
    {
        return $this->hasOne(User::className(), ['id' => 'manager_id']);
    }

    public function getGroupRelated()
    {
        return $this->hasOne(TestingUserGroupAssign::className(), ['user_id' => 'id'])->andWhere([
        	TestingUserGroupAssign::tableName() . '.session_id' => Yii::$app->request->get('session')
        ]);
    }

    public function getHistory()
    {
        return $this->hasOne(TestingSendHistory::className(), ['user_id' => 'id'])->andWhere([
        	TestingSendHistory::tableName() . '.session_id' => Yii::$app->request->get('session')
        ]);
    }

	public function getFio() 
	{
		return $this->last_name . ' ' . $this->first_name . ' ' . $this->patronymic;
	}

	public function generateLogin() 
	{
		return 'testlogin' . $this->id;
	}

	public function assignedTestsForSession($session_id) 
	{
		$cr = new CDbCriteria;
		$cr->with = 'passings';
		$cr->addCondition('passings.user_id = :user_id');
		$cr->addCondition('session_id = :session_id');
		$cr->params = array(
			':user_id'=>$this->id,
			':session_id'=>$session_id,
		);
		$cr->together = true;
		$cr->group = 't.id';
		return TestingTest::model()->findAll($cr);
	}

	public static function getCompaniesList() 
	{
		return Yii::app()->db->createCommand('SELECT DISTINCT  `company_name` FROM  `testings_users`')->queryAll();
	}
}
