<?php
namespace common\modules\users\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\data\ActiveDataProvider;
use himiklab\sortablegrid\SortableGridBehavior;
use \common\components\validators\RuEmailValidator;

use \common\modules\rbac\models\AuthItem;
use \common\modules\rbac\models\AuthAssignment; 

class User extends \common\components\ActiveRecordModel implements IdentityInterface
{
	const PAGE_SIZE = 10;

	const STATUS_ACTIVE = 'active';
	const STATUS_NEW     = 'new';
	const STATUS_BLOCKED = 'blocked';

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

//	public $email;
	public $role;
	public $password_c;
	public $captcha;
	public $remember_me = false;
	public $activate_error;
//	public $activate_code;
//	public $checked;
	public $csv_file;
	public $send_email;
	public $generate_new;
//	public $fio;
//	public $phone;
//	public $mobile_phone;
//	public $skype;
	public $tmp; //for external using. no delete!
//	public $post;

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
            "status" => "Активен / заблокирован"
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
				self::SCENARIO_UPDATE,
				self::SCENARIO_SEND_NEW_PASSWORD,
				self::SCENARIO_RECOVER_PASSWORD
			], 'message' => 'Пожалуйста, укажите корректный e-mail адрес'],
			[['send_email'], 'safe'], 
			[['fio','name', 'surname'], 'safe', 'on' => [
				self::SCENARIO_REGISTRATION,
				self::SCENARIO_CREATE,
			], 'message' => 'Пожалуйста, укажите Ваше имя'],
			[['phone'], 'required', 'on' => [
				self::SCENARIO_REGISTRATION,
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
			[['password'], 'string', 'min' => 4, 'on'  => [
				self::SCENARIO_REGISTRATION,
				self::SCENARIO_CHANGE_PASSWORD,
				self::SCENARIO_UPDATE,
				self::SCENARIO_CREATE,
			]],
			//array('password', 'unsafe', 'on'  => array(
			[['password'], 'safe', 'on'  => [
				self::SCENARIO_UPDATE,
			]],
			/*[['email'], 'email', 'message' => $this->emailErrorMessage(), 'on'=> [
				self::SCENARIO_RECOVER_PASSWORD,
				self::SCENARIO_SEND_NEW_PASSWORD,
				self::SCENARIO_LOGIN,
			]],*/
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
			//array('phone, mobile_phone, phone_ext, fax', 'PhoneValidator'),
			['is_deleted, date_delete', 'safe', 'on' => [
				self::SCENARIO_DELETE,
			]],
			[['is_deleted'], 'integer','integerOnly' => true],
			//array('phone', 'integer'),
			[['fio'], 'string','min' => 2],
			[['email'], 'string','max' => 200],
			[['status'], '\yii\validators\RangeValidator', 'range' => ['active','new','blocked'], 'allowArray' => true],
			[['activate_code'], 'safe'],
			[['email'], 'filter','filter' => 'trim'],
			[['csv_file'], 'file', 'mimeTypes' => 'csv', 'on' => [
				self::SCENARIO_CSV_IMPORT,
			]],
//			[['fio', 'phone',' mobile_phone'], 'filter', 'filter' => 'strip_tags'],
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
        return static::findOne(['email' => $username, 'status' => self::STATUS_ACTIVE]);
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
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }
	
	public function getPost() {
		if(!empty($this->post))
			return $this->post;
		else
			return 'Должность не определена';
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


	public function getCustomName($user = null)
	{
		if (!$user)
			$user = $this;

        if(empty($user->name) && empty($user->surname))return $user->email;

		return  $user->name . ' ' . $user->surname;
	}


	public function getShortName()
	{
		return $this->fio;
	}


	public function getUserDir()
	{
		$dir  = "upload/users/" . $this->id . "/";
		$path = $_SERVER["DOCUMENT_ROOT"] . $dir;

		if (!file_exists($path))
		{
			mkdir($path);
			chmod($path, 0777);
		}

		return $dir;
	}

	public function emailErrorMessage()
	{
		if ($this->scenario == self::SCENARIO_LOGIN)
			return 'Вы ввели некорректный логин или пароль';
		else
			return 'Введенный E-mail адрес некорректен. Пожалуйста, проверьте правильность ввода';
	}


	public function relations()
	{
		return array(
			'assignment' => array(self::HAS_ONE, 'AuthAssignment', 'userid'),
			'city'       => array(self::BELONGS_TO, 'City', 'city_id')
		);
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
				'pageSize'=> 2
			]
		]);

		$query->andFilterWhere(['email' => $this->email]);
		$query->andFilterWhere(['date_create' => $this->date_create]);
		
		return $dataProvider;
	}

	public function generateActivateCode()
	{
		$this->activate_code = md5($this->id . $this->name . $this->email . time(true) . rand(5, 10));
	}


	public function getRole()
	{
		/*$assigment = AuthAssignment::find(['userid' => $this->id])->one();

		if (!$assigment)
		{
			$assigment           = new AuthAssignment();
			$assigment->item_name = AuthItem::ROLE_DEFAULT;
			$assigment->user_id   = $this->id;
			$assigment->save(false);
		}

		return $assigment->role;*/
		return 'admin';
	}
	
	public function getRoleName()
	{
		$assigment = AuthAssignment::find(['user_id' => $this->id])->one();

		if (!$assigment)
		{
			$assigment           = new AuthAssignment();
			$assigment->item_name = AuthItem::ROLE_DEFAULT;
			$assigment->user_id   = $this->id;
			$assigment->save(false);
		}
		$roles = \Yii::$app->authManager->getRoles($this->id);
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


	public function sendActivationMail()
	{
		$mailler_letter = MailerLetter::model();

		$subject = Setting::model()->getValue(self::SETTING_REGISTRATION_MAIL_SUBJECT);
		$subject = $mailler_letter->compileText($subject);

		$body = Setting::model()->getValue(self::SETTING_REGISTRATION_MAIL_BODY);
		$body = $mailler_letter->compileText($body, array('user' => $this));

		MailerModule::sendMail($this->email, $subject, $body);
	}


	public function activateAccountUrl()
	{
		$url = 'http://' . $_SERVER['HTTP_HOST'];
		$url .= Yii::app()->controller->url(
			'/activateAccount/' . $this->activate_code . '/' . md5($this->email)
		);

		return $url;
	}


	public function changePasswordUrl()
	{
		$url = 'http://' . $_SERVER['HTTP_HOST'];
		$url .= Yii::app()->controller->url(
			'/changePassword/' . $this->password_change_code . '/' . md5($this->email)
		);

		return $url;
	}

    public function beforeSave($insert){
        
        if( strtolower($this->scenario) == 'update'){
            if(isset($this->password) && empty($this->password))
                unset($this->password);
            
        }

        return parent::beforeSave($insert);
    }

	public function beforeDelete()
	{
		if (parent::beforeDelete())
			return true;

		return false;
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

