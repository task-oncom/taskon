<?php
namespace common\modules\users\models;

use common\components\UnisenderAPI;
use common\modules\messageTemplate\controllers\TemplateAdminController;
use common\modules\messageTemplate\models\MessageTemplate;
use common\modules\triggers\components\conditions\Conditions;
use common\modules\triggers\components\conditions\conditions\CheckUserToRegistration;
use common\modules\triggers\models\TriggerCondition;
use common\modules\triggers\models\TriggerLogs;
use common\modules\triggers\models\TriggerSchedule;
use common\modules\triggers\models\TriggerTrigger;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\IdentityInterface;
use yii\data\ActiveDataProvider;
use himiklab\sortablegrid\SortableGridBehavior;
use \common\components\validators\RuEmailValidator;
use \common\modules\messageTemplate\components\Templates;
use common\models\Settings;

use \common\modules\rbac\models\AuthItem;
use \common\modules\rbac\models\AuthAssignment;
use \common\modules\eauth\models\UserEAuth;
use yii\web\UrlManager;

class User extends \common\components\ActiveRecordModel implements IdentityInterface
{
    public $created_at;
    public $updated_at;
    
    const PAGE_SIZE = 10;

    const CACHE_SCORE = 'user.scores.';
    
    const OCCUPATION_CHIEF_IT = 1;
    const OCCUPATION_MANAGER_IT = 2;
    const OCCUPATION_CREATOR = 3;
    const OCCUPATION_NOT_IT = 4;
    
    const STATUS_ACTIVE = 'active';
    const STATUS_NEW     = 'new';
    const STATUS_BLOCKED = 'blocked';

    const ROLE_ADMIN = 'admin';
    const ROLE_USER     = 'user';
    const ROLE_SUPPORT     = 'support';

    const GENDER_MAN   = "man";
    const GENDER_WOMAN = "woman";

    const SETTING_CHANGE_PASSWORD_REQUEST_MAIL_SUBJECT     = 'change_password_request_mail_subject';
    const SETTING_CHANGE_PASSWORD_REQUEST_MAIL_BODY        = 'change_password_request_mail_body';
    const SETTING_CHANGE_PASSWORD_REQUEST_MAIL_BODY_SINGLE = 'change_password_request_mail_body_single';
    const SETTING_ACTIVATE_REQUEST_DONE_MESSAGE            = 'activate_request_done_message';
    const SETTING_REGISTRATION_MAIL_SUBJECT                = 'registration_mail_subject';
    const SETTING_REGISTRATION_DONE_MESSAGE                = 'registration_done_message';
    const SETTING_REGISTRATION_MAIL_BODY                   = 'registration_mail_body';

    const SCENARIO_CHANGE_PASSWORD_REQUEST = 'ChangePasswordRequest';
    const SCENARIO_SEND_NEW_PASSWORD       = 'SendNewPassword';
    const SCENARIO_ACTIVATE_REQUEST        = 'ActivateRequest';
    const SCENARIO_CHANGE_PASSWORD         = 'ChangePassword';
    const SCENARIO_REGISTRATION            = 'Registration';
    const SCENARIO_UPDATE                  = 'Update';
    const SCENARIO_CREATE                  = 'Create';
    const SCENARIO_LOGIN                   = 'Login';
    const SCENARIO_DELETE                  = 'Delete';
    const SCENARIO_SEARCH                  = 'Search';
    const SCENARIO_CSV_IMPORT              = 'CSV_IMPORT';
    const SCENARIO_RECOVER_PASSWORD	    = 'RecoverPassword';
    const SCENARIO_SOCIAL_REGISTRATION            = 'SocialRegistration';
    const SOURCE_DEFAULT = 'direct';
    const SOURCE_MESSAGE= 'email';

    public $password_c;
    public $captcha;
    public $remember_me = false;
    public $activate_error;
    public $csv_file;
    public $send_email;
    public $generate_new;
    public $tmp; //for external using. no delete!

    public $profile;
    public $fullName;

    public static $role_list = [
    	self::ROLE_ADMIN => 'Доступ в админ-панель',
    	self::ROLE_USER => 'Доступ к сайту',
    	self::ROLE_SUPPORT => 'Клиент тех. поддержки',
    ];
    
     public static $occupation_list = [
        self::OCCUPATION_CHIEF_IT => 'Руководитель в IT сфере',
        self::OCCUPATION_MANAGER_IT => 'Менеджер в IT сфере',
        self::OCCUPATION_CREATOR => 'Разработчик',
        self::OCCUPATION_NOT_IT => 'Не работаю в IT',
    ];
     
    public function behaviors()
    {
        return [
            'eavAttributes' => [
                'class' => '\lagman\eav\EavBehavior',
                'valueClass' => 'User',
            ],
            'sort' => [
                'class' => SortableGridBehavior::className(),
                'sortableAttribute' => 'sort'
            ],
        ];
    }

	public function attributeLabels()
	{
		$attrs = array_merge(parent::attributeLabels(), array(
			"password_c"   => "Пароль еще раз",
			"fio"          => "Фамилия имя отчество",
            "name"          => "Имя",
            "surname"       => "Фамилия",
			"remember_me"  => "Запомни меня",
			"csv_file"     => "CVS Файл",
			"captcha"      => "Введите код",
			"role"         => "Группа пользователей",
			"send_email"   => "Отправить уведомление на почту",
			"generate_new"   => "Сгенерировать пароль автоматически",
			"post"   => "Должность",
            "status" => "Активен / заблокирован",
            "occupation"   => "Род занятий",
            "phone"   => "Телефон",
            "mobile_phone"   => "Мобильный телефон",
		));
		
		return $attrs;
	}

	public function rules()
	{
		return [
			[['email'], 'required', 'on' => [
				self::SCENARIO_ACTIVATE_REQUEST,
				self::SCENARIO_CHANGE_PASSWORD_REQUEST,
				self::SCENARIO_CREATE,
				self::SCENARIO_LOGIN,
				self::SCENARIO_REGISTRATION,
                self::SCENARIO_SOCIAL_REGISTRATION,
				self::SCENARIO_UPDATE,
				self::SCENARIO_SEND_NEW_PASSWORD,
				self::SCENARIO_RECOVER_PASSWORD
			], 'message' => 'Пожалуйста, укажите корректный e-mail адрес'],
			[['send_email', 'fullName', 'last_logon'], 'safe'],
			[['fio','name', 'surname'], 'safe', 'on' => [
				self::SCENARIO_CREATE,
			], 'message' => 'Пожалуйста, укажите Ваше имя'],
            [['name'], 'required', 'on' => [
				self::SCENARIO_REGISTRATION,
                self::SCENARIO_SOCIAL_REGISTRATION,
			], 'message' => 'Пожалуйста, укажите Ваше имя'],
			[['phone'], 'required', 'on' => [
				self::SCENARIO_CREATE,
			], 'message' => 'Пожалуйста, укажите Ваш номер телефона'],
			[['phone'], 'string', 'max'=> 50],
			[['mobile_phone'], 'string', 'max'=> 50],
			[['post'], 'string', 'max'=> 60],
			[['skype'], 'string', 'max'=> 20],
			[['fio'], 'string', 'max' => 40],
			//array('first_name, last_name, patronymic','RuLatAlphaValidator'),
			[['password_c'], 'required','on' => [
				self::SCENARIO_REGISTRATION,
				self::SCENARIO_CHANGE_PASSWORD,
				self::SCENARIO_CREATE,
			], 'message' => 'Пожалуйста, укажите пароль повторно'],
			[['password'], 'required', 'on' => [
				self::SCENARIO_LOGIN,
				self::SCENARIO_REGISTRATION,
				self::SCENARIO_CHANGE_PASSWORD,
				self::SCENARIO_CREATE,
			], 'message' => 'Пожалуйста, укажите пароль'],
			[['password'], 'string', 'min' => 7, 'on'  => [
				self::SCENARIO_REGISTRATION,
				self::SCENARIO_CHANGE_PASSWORD,
				self::SCENARIO_UPDATE,
				self::SCENARIO_CREATE,
			]],
			[['password'], 'safe', 'on'  => [
				self::SCENARIO_UPDATE,
			]],
			[['email'], 'unique','on' => [
				self::SCENARIO_REGISTRATION,
				self::SCENARIO_CREATE,
			]],
			[['email'], '\common\components\validators\RuEmailValidator'],
			[['password_c'], 'compare', 'compareAttribute' => 'password', 'on' => [
				self::SCENARIO_REGISTRATION,
				self::SCENARIO_CHANGE_PASSWORD,
				self::SCENARIO_UPDATE,
				self::SCENARIO_CREATE,
				self::SCENARIO_SEND_NEW_PASSWORD,
			], 'message' => 'Пароли должны совпадать и состоять из букв латинского алфавита или цифр.'],
			[['password'], 'safe', 'on' => self::SCENARIO_CSV_IMPORT],
			['is_deleted, date_delete', 'safe', 'on' => [
				self::SCENARIO_DELETE,
			]],
			[['is_deleted'], 'integer','integerOnly' => true],
			[['fio'], 'string','min' => 2],
			[['email'], 'string','max' => 200],
			[['status'], '\yii\validators\RangeValidator', 'range' => ['active','new','blocked'], 'allowArray' => true],
			[['activate_code'], 'safe'],
			[['email'], 'filter','filter' => 'trim'],
			[['csv_file'], 'file', 'mimeTypes' => 'csv', 'on' => [
				self::SCENARIO_CSV_IMPORT,
			]],
			[['id', 'email', 'status', 'date_create', 'fio'], 'safe', 'on'=> [
				self::SCENARIO_SEARCH,
				self::SCENARIO_CREATE,
			]],
			[['tmp','role','sort','fio','name', 'surname','email', 'mobile_phone'], 'safe'],
		];
	}
	
	public function getEavAttributes() {
		return $this->hasMany(\lagman\eav\DynamicModel::className(), ['customer_id' => 'id']);
	}
	
	public static $status_list = array(
		self::STATUS_ACTIVE  => "Активный",
		//self::STATUS_NEW     => "Новый",
		self::STATUS_BLOCKED => "Заблокирован"
	);

	/**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }
	
	/**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['email' => $username/*, 'status' => self::STATUS_ACTIVE*/]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_change_code' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) 
        {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_change_code = Yii::$app->security->generateRandomString() . '_' . time();
        $this->password_change_date = date('Y-m-d H:i:s');
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_change_code = null;
    }
	
	public function getPost() 
	{
		if(!empty($this->post))
		{
			return $this->post;
		}
		else
		{
			return 'Должность не определена';
		}
	}
	
	/**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
	
	/**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }
	
	/**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
	
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function tableName()
	{
		return 'users';
	}

	public function name()
	{
		return "Пользователи";
	}

	public function beforeValidate()
    {
    	if($this->status != static::STATUS_ACTIVE)
    	{
    		$this->status = static::STATUS_BLOCKED;
    	}

        return parent::beforeValidate();
    }

	public function getFullName()
	{
		return  $this->name . ' ' . $this->surname;
	}

	public function setFullName($value)
	{
		$this->fullName = $value;
	}

	public function sendPassword()
	{
		return Yii::$app->mailer->compose(['html' => 'sendNewPassword-html', 'text' => 'sendNewPassword-text'], ['user' => $this])
            ->setFrom([Settings::getValue('setting-info-email') => Settings::getValue('setting-from-email')])
            ->setTo($this->email)
            ->setSubject('Данные для входа')
            ->send();
	}

	public function getCustomName($user = null)
	{
		if (!$user)
			$user = $this;

        if(empty($user->name) && empty($user->surname))return $user->email;

		return  $user->name . ' ' . $user->surname;
	}

    public function getAssignment()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id'=>'id']);
    }

	public function search($params)
	{
		$query = User::find();

		$dataProvider = new ActiveDataProvider([
			'query'  => $query,
			'sort'      => [
				'attributes' => [
					'role' => [
						'asc'  => 'role.name',
						'desc' => 'role.name DESC'
					], '*'
				]
			],
			'pagination'=> [
				'pageSize'=> 100
			]
		]);

        $query->joinWith('assignment');
        $query->andWhere('auth_assignment.item_name = "user"');
		$query->andFilterWhere(['email' => $this->email]);
		$query->andFilterWhere(['date_create' => $this->date_create]);


		return $dataProvider;
	}

	public function generateActivateCode()
	{
		$this->activate_code = md5($this->id . $this->name . $this->email . time(true) . rand(5, 10));
	}
	
	public function getRoleName()
	{
		$assigment = AuthAssignment::findAll(['user_id' => $this->id]);
		if (!$assigment)
		{
			$assigment           = new AuthAssignment();
			$assigment->item_name = AuthItem::ROLE_DEFAULT;
			$assigment->user_id   = $this->id;
			$assigment->save(false);
		}
		$roles = \Yii::$app->authManager->getRolesByUser($this->id);
		$out = '';
		foreach($roles as $key=>$role) {
			$out .= $role->description . ', ';
		}
		return $out;
	}

	public function isRootRole()
	{
		return $this->role->name == AuthItem::ROLE_ROOT;
	}

	public function getFio()
	{
		$result = $this->name;
		if (isset($this->surname) && strlen($this->surname)>0) {
			$result .= ' '.$this->surname;
		}
		return $result;
	}

    public static function getUsersByRole($moduleName) {
        $users = self::find()->all();
        $granted = [];
        foreach($users as $user) {
            if(\Yii::$app->authManager->checkAccess($user->id,$moduleName))
                $granted[] = $user;
        }
        return $granted;
    }
}

