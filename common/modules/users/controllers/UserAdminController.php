<?php
namespace common\modules\users\controllers;

use yii\helpers\Url;
use common\modules\users\models\User;
use common\models\LoginForm;
use common\modules\users\forms\UserForm;
use common\modules\rbac\models\AuthAssignment;
use yii\filters\AccessControl;
use Yii;
use yii\filters\VerbFilter;
use himiklab\sortablegrid\SortableGridAction;

class UserAdminController extends \common\components\AdminController
{
    public static function actionsTitles()
    {
        return array(
            "Login"           => "Авторизация",
            "Manage"          => !empty($_GET['is_deleted'])?"Удаленные пользователи":"Все пользователи",
            "View"            => "Просмотр пользователя",
            "Create"          => "Добавление пользователя",
            "Update"          => "Редактирование пользователя",
            "Delete"          => "Безвозвратное удаление пользователя",
            "SendNewPassword" => "Безвозвратное удаление пользователя",
            "SetDeletedFlag"  => "Удаление и восстановление пользователя",
            "ImportCSV"       => "Импорт пользователей из CSV-файла",
            "ImportCSV1"      => "Импорт пользо",
            "Test"      	  => "Импорт пользо",
            "Import"          => "Импорт пользователей из CSV-файла",
			"Captcha"		  => "",
			"Sort"			  => "",
            "Block"           => "",
        );
    }

	/**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionBlock($id) {
        $model = User::find()->where(['id'=>$id])->one();
        if(empty($model)) $error;

        $model->status = 'blocked';
        $model->save(false);
        $this->redirect(['/rbac/role-admin/manage']);
    }
	
	public function actions()
    {
        return [
            'captcha' => [
                'class' => '\yii\captcha\CaptchaAction',
				//'captchaAction' => '/users/user-admin/captcha',
            ],
			'sort' => [
				'class' => \himiklab\sortablegrid\SortableGridAction::className(),
				'modelName' => \common\modules\users\models\User::className(),
			],
        ];
    }

    public function actionSendNewPassword($id)
    {
        $model           = $this->loadModel($id);
        $model->scenario = User::SCENARIO_SEND_NEW_PASSWORD;

        $form = new BaseForm('users.SendNewPasswordForm', $model);
        //$this->performAjaxValidation($model);
/*
		print_r($form);
		die();
*/
        if(isset($_POST['User'])) {
			if (isset($_POST['User']['password']) && isset($_POST['User']['password_c'])) {
				$model->password = $_POST['User']['password'];
				$model->password_c = $_POST['User']['password_c'];
			} else {
				$model->password = 123;
				$model->password_c = 123;
			}
			if ($model->validate()) {
				if ($_POST['User']['generate_new'] == 1) {
					$password = PasswordGenerator::generate(7);
				} else {
					$password = $_POST['User']['password'];
				}
				$model->password = md5($password);
				$model->password_c = md5($password);
				if ($model->save()) {
					Yii::app()->user->setFlash('flash','Пароль для пользователя <b>'.$model->name.'</b> был изменён.');

					$email = Yii::app()->email;
					$email->to = $user;
					$email->from = Setting::getValue('support_email');
					$email->subject = 'Hello';
					$email->message = Yii::app()->controller->renderInternal(Yii::getPathOfAlias('application.views.yii-mail.pass').'.php', array('password' => $password), true);
					$email->send();
					$this->redirect('/users/userAdmin/manage');
				}
			}

        }

        $this->render('sendNewPassword', array('form' => $form));
    }


    public function actionImport()
    {
        $olds = UserOld::model()->findAll();
        $new  = new User;
        $auth = Yii::app()->authManager;

        //add role
        foreach ($olds as $old)
        {
            if ($new->findByAttributes(array('login'=> $old->login)))
            {
                continue;
            }
            $new->isNewRecord = true;
            $new->id          = null;

            if ($old->howtoappeal == 'Уважаемая')
            {
                $new->gender = 'men';
            }
            elseif ($old->howtoappeal == 'Уважаемый')
            {
                $new->gender = 'women';
            }
            else
            {
            }

            //city
            $city = City::model()->findByAttributes(array('name'=> $old->city));
            if (!$city)
            {
                $city       = new City;
                $city->name = $old->city;
                $city->save();
            }
            $new->city_id = $city->id;

            $new->password    = md5($old->pass);
            $new->status      = $old->isAct = 1 ? 'active' : 'new';
            $new->date_create = date('Y-m-d H:i:s', strtotime($old->regdate));
            $fields           = array(
                'company'     => 'company',
                'delflag'     => 'is_deleted',
                'post'        => 'post',
                'name'        => 'first_name',
                'surname'     => 'last_name',
                'patron'      => 'patronymic',
                'email'       => 'email',
                'login'       => 'login',
                'postindex'   => 'postindex',
                'address'     => 'address',
                'phone1'      => 'phone',
                'phone2'      => 'phone_ext',
                'fax'         => 'fax',
            );
            foreach ($fields as $key=> $val)
            {
                $new->$val = $old->$key;
            }

            $new->save(false);

            switch ($old->grid)
            {
                case 1:
                    $role = 'schneider_electric';
                    break;
                case 2:
                    $role = 'diller';
                    break;
                case 4:
                    $role = 'residential';
                    break;
                case 5:
                    $role = 'test';
                    continue;
                    break;
                case 6:
                    $role = 'eds';
                    break;
                case 7:
                    $role = 'e_commerce';
                    break;
                case 8:
                    $role = 'admin_sertifikate';
                    break;
                case 9:
                    $role = 'admin';
                    break;
                case 11:
                    $role = 'do_dor';
                    break;
                case 15:
                    $role = 'urezanni_dostup';
                    break;
                case 20:
                    $role = 'prom_partners';
                    break;
                case 21:
                    $role = 'admin_sklad';
                    break;
                case 22:
                    $role = 'si';
                    break;
                case 24:
                    $role = 'admin_tarif';
                    break;
            }

            $auth->assign($role, $new->id);
        }
    }


    public function actionLogin()
    {

        if (!Yii::app()->user->isGuest)
        {
            throw new CException('Вы уже авторизованы!');
        }

        $this->layout = "//layouts/adminLogin";

        $model = new User("Login");

        $params = array(
            "model"      => $model,
            "error_code" => null
        );

        if (isset($_POST["User"]))
        {
            $model->attributes = $_POST["User"];
            if ($model->validate())
            {
                $identity = new UserIdentity($_POST["User"]["email"], $_POST["User"]["password"], $_POST["User"]["remember_me"]);


                if ($identity->authenticate(false))
                {
                    Yii::app()->user->setState("_allowToUseTiny", (Yii::app()->user->checkAccess('admin')));  
                    $this->redirect($this->url("/main/mainAdmin"));
                }
                else
                {
                    $params["error_code"] = $identity->errorCode;
                }
            }
        }

        $this->render("login", $params);
    }

	public function actionTest(){
		$items = array();
		$model = AuthAssignment::model()->findAll();
		foreach($model as $item){
			$items[$item->userid][] = $item->itemname;
		}
		foreach($items as $key=>$value){
			if(count($value)>1){
				print_r($value);
				echo $key.'<br/>';}
		}
	}

    public function actionManage($is_deleted = 0)
    {

		//$is_deleted = $this->getRequest()->getQueryParam('is_deleted') ? $this->getRequest()->getQueryParam('is_deleted') : 0;

        $model = new \common\modules\users\models\User(/*User::SCENARIO_SEARCH*/);
		$model->scenario = User::SCENARIO_SEARCH;
        
        $model->is_deleted = $is_deleted;

        $model->attributes = $this->getRequest()->getQueryParams();
		
		\yii::$app->controller->breadcrumbs = [
					$is_deleted == 0 ? 'Все пользователи':'Удаленные пользователи',
				];

        return $this->render('manage', array(
            'is_deleted' => $is_deleted,
            'model'      => $model,
        ));
    }


    public function actionView($id)
    {
        $this->render('view', array(
            'model'=> $this->loadModel($id),
        ));
    }


    public function actionCreate()
    {
        $model           = new User;
        $model->scenario = User::SCENARIO_CREATE;
        $model->status = "active";
        $model->role = "manager";
if (!isset($_POST['User']))
	$model->send_email = true;
        $form = '';
        $this->performAjaxValidation($model);
        
		\Yii::$app->controller->page_title = 'Добавить пользователя';

		\Yii::$app->controller->tabs = array(
			"управление пользователями" => Url::toRoute("manage"),
		);
		\yii::$app->controller->breadcrumbs = [
					['Все пользователи' => '/users/user-admin/manage'],
					'Новый пользователь',
				];

        if (isset($_POST['User']))
        {
            $model->attributes = $_POST['User'];

            if ($model->validate())
            {
                $password = $model->password;

                $model->password = \Yii::$app->security->generatePasswordHash($model->password);
		        $model->activate_code=\Yii::$app->security->generatePasswordHash($model->password.'xdf5sf');
                if(!$model->save(false)) die(print_r($model->error));

                /*$assignment           = new AuthAssignment();
                $assignment->item_name = $model->role;
                $assignment->user_id   = $model->id;
                $assignment->save();*/

                /*if ($model->send_email)
                {

                    //$this->saveEmailToNewUser($model, $password);
                    $email = Yii::app()->email;
                    $email->to = $model->email;
                    $email->from = Setting::getValue('admin_email');
                    $email->subject = 'Авторизация пользователя на '.Setting::getValue('site_url');
                    if($model->status == "new")
                    $email->message = Yii::app()->controller->renderInternal(Yii::getPathOfAlias('application.views.yii-mail.activate').'.php', array('activateCode' => $model->activate_code,'user' => $model,'password' => $password), true);
                    if($model->status == "active")
                    $email->message = Yii::app()->controller->renderInternal(Yii::getPathOfAlias('application.views.yii-mail.active_already').'.php', array('activateCode' => $model->activate_code,'user' => $model,'password' => $password), true);


                    $email->send();
                }*/
                $this->redirect(array(
                    '/rbac/role-admin/manage',
                    'id' => $model->id,
                    'is_created'=>1
                ));
            }
            //else die(print_r($model->errors));
        }
        $form = new \common\components\BaseForm('/common/modules/users/forms/UserForm', $model);
        return $this->render('create', array('form' => $form->out, 'model' => $model));
    }


    private function saveEmailToNewUser($user, $password)
    {
        $body    = Setting::getValue('email_to_new_user');
        $subject = Setting::getValue('email_to_new_user_subject');

        $mailer_letter = MailerLetter::model();
        $body          = $mailer_letter->compileText($body, array(
            'gender'    => 'Уважаемый(ая)',
            'user'      => $user,
            'password'  => $password
        ));
        MailerModule::sendMail($user->email, $subject, $body);
    }


    public function actionUpdate($id)
    {
        $model             = $this->loadModel($id);
        $model->password_c = $model->password;
        $model->scenario   = User::SCENARIO_UPDATE;
		
		$model->role = $model->getRole();

        $old_password = $model->password;
        $this->performAjaxValidation($model);
		
		\yii::$app->controller->page_title = 'Редактирование пользователя <small>' . $model->name.'</small>';

		\yii::$app->controller->tabs = [
			"управление пользователями" => \yii\helpers\Url::toRoute("manage"),
		];
		\yii::$app->controller->breadcrumbs = [
					['Все пользователи' => '/users/user-admin/manage'],
					$model->name,
				];

        if (isset($_POST['User']))
        {
            $model->attributes = $_POST['User'];
            $pwd = \Yii::$app->security->generatePasswordHash($_POST['User']['password']);
	        $model->password_c = $pwd;
	        $model->password = $pwd;

            if ($model->validate())
            {
                if ($_POST['User']['password'] != $old_password)
                {
                    $model->password = \Yii::$app->security->generatePasswordHash($_POST['User']['password']);
                }

                $model->save(false);

                AuthAssignment::updateUserRole($model->id, $_POST['User']['role']);

                $this->redirect(array(
                    '/rbac/role-admin/manage',
                    'id'=> $model->id
                ));
            }
        }
//die(print_r(\yii::getAlias($this->module->id)));
        $form = new \common\components\BaseForm('/common/modules/users/forms/UserForm', $model);
		//$form = ActiveFormMy::widget(['options'=>]);
		//$form = '\common\modules\users\forms\UserForm';
        //unset($form->spsFields['captcha']);
//die(print_r($form->out));
        return $this->render('update', array(
            'form'  => $form->out,
            'model' => $model,
        ));
    }


    public function actionDelete($id)
    {
        $model = User::find()->where(['id'=>$id])->one();
        $model->delete();

        if (!isset($_GET['ajax']))
        {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['/rbac/role-admin/manage']);
        }
    }


    public function actionSetDeletedFlag($id, $is_deleted)
    {
        $model              = $this->loadModel($id);
        $model->scenario    = User::SCENARIO_DELETE;
        $model->is_deleted  = $is_deleted;
        $model->date_delete = new CDbExpression('NOW()');
        $model->save(false);

	    $this->redirect($this->createUrl('manage'));
    }


    public function actionImportCSV()
    {
        $model           = User::model();
        $model->scenario = User::SCENARIO_CSV_IMPORT;

        $form = new BaseForm('users.ImportCSVForm', $model);

        $params = array('form' => $form);

        if (isset($_POST['User']))
        {
            $model->attributes = $_POST['User'];

            if ($model->validate())
            {
                $csv_file = CUploadedFile::getInstance($model, 'csv_file');

                $labels = array('gender', 'last_name', 'first_name', 'patronymic', 'company', 'city_id', 'postindex', 'address', 'email', 'login', 'password', 'post', 'phone', 'phone_ext', 'fax', 'status');

                $users = array();

                $attr_labels = $model->attributeLabels();

                $resource = CSVHelper::open($csv_file->tempName);
                $data     = CSVHelper::fgetcsv($resource);
                $data     = CSVHelper::fgetcsv($resource);

				try {
                    while ($data = CSVHelper::fgetcsv($resource)) {
                        if (count($data) < 2) break;
                        if (!($data[0] || $data[1] || $data[2] || $data[3] || $data[4])) continue;
                        if (!$labels) {
                            $labels = $data;
                            foreach ($labels as $i => $label) {
								$labels[$i] = in_array($label, $attr_labels) ? array_search($label, $attr_labels) : null;
                            }
							continue;
                        }

                        $user = array();
						foreach ($data as $i => $value) {
                            if (!$labels[$i]) continue;
                            $user[$labels[$i]] = $value;
                        }

                        $users[] = $user;
                    }

                    $params = array('attr_labels' => $attr_labels, 'users' => $users, 'send_email' => $model->send_email);
                } catch (Exception $e){
                    echo 'Импорт прошел неудачно, проверьте структуру файла-шаблона:' . $e->getMessage();
                }
            }
        }
        else if (isset($_POST['users']))
        {
            $count = 0;
            foreach ($_POST['users'] as $data)
            {
                if (!isset($data['checked'])) continue;
                $count++;

                $user = User::model()->findByAttributes(array('login' => $data['login']));
                if ($user===null)
                    $user = new User;

                $user->attributes = $data;
                $user->validate();


                if ($data['password']) {
					$user->password = md5($data['password']);
				} else {
					$data['password'] = PasswordGenerator::generate(7);
					$user->password = md5($data['password']);
				}


                if ($data['gender'])
					$user->gender = in_array($data['gender'], array('м', 'М', 'm', 'M')) ? User::GENDER_MAN : User::GENDER_WOMAN;

				$city_id = trim($data['city_id']);

                if (!empty($city_id))
                {
                    $city = City::model()->findByAttributes(array('name' => $city_id));
                    if (!$city)
                    {
                        $city       = new City;
                        $city->name = $city_id;
                        $city->save();
                    }
                }
                $user->is_deleted = 0;

                $user->save(false);
                if ($_POST['send_email'])
					$this->saveEmailToNewUser($user, $data['password']);

                if (!Yii::app()->authManager->isAssigned($_POST['role'], $user->id))
					Yii::app()->authManager->assign($_POST['role'], $user->id);
            }
            Yii::app()->user->setFlash('import_done', "Импорт данных завершен! Импортировано: $count записей");
        }
        $this->render('importCSV', $params);
    }

}