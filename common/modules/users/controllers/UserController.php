<?php
namespace common\modules\users\controllers;
class UserController extends \common\components\BaseController {
    const ERROR_PASSWORD_RECOVER_AUTH = 'Вы не можете восстановить пароль будучи авторизованным!';
    public $cats;
    public $layout = '/layouts/content';
    public $feedback_form;

//
//    public function filters()
//    {
//    	return array('accessControl');
//    }
//
//    public function accessRules()
//    {
//        return array(
//            array('deny',
//	        	  'actions' => array(
//	              	  'ActivateAccountRequest',
//	        		  'ChangePasswordRequest',
//	        		  'ActivateAccount',
//	        		  'Registration',
//	        		  'ChangePassword',
//	        		  'Login'
//	        	  ),
//	        	  'users' => array('@')
//        	)
//        );
//    }
//


    public static function actionsTitles() {
        return array(
            "Login" => "Авторизация",
            "Logout" => "Выход",
            "Registration" => "Регистрация",
            "ActivateAccount" => "Активация аккаунта",
            "ActivateAccountRequest" => "Запрос на активацию аккаунта",
            "ChangePassword" => "Смена пароля",
            "ChangePasswordRequest" => "Запрос на смену пароля",
            "RecoverPassword" => "Востановления пароля",
            "Updateprofile" => "зменение личных данных",
        );
    }


    public function loadModel($id) {
        $model = User::model()->findByPk((int)$id);
        if ($model === null) {
            $this->pageNotFound();
        }

        return $model;
    }


    public function actionLogin() {
        if (!Yii::app()->user->isGuest) {
            throw new CException('Вы уже авторизованы!');
        }
        $model = new User;
        $form = new BaseForm('users.LoginForm', $model);
        $params = array(
            "model" => $model,
            "error_code" => null
        );

        if (isset($_POST["LoginForm"])) {
            $formValues = $_POST["LoginForm"];
        } elseif (isset($_POST["User"])) {
            $formValues = $_POST["User"];
        } else {
            $formValues = false;
        }

        if ($formValues) {
            $model->attributes = $formValues;
            if ($model->validate()) {
                $identity = new UserIdentity($formValues["email"], $formValues["password"]);


                if ($identity->authenticate(false)) {
                    if (Yii::app()->user->getRole() == 'user')
                        $this->redirect('/cabinet');
                    else
                        $this->redirect($this->url("/main/mainAdmin"));
                } else {
                    $params["error_code"] = $identity->errorCode;
                    $model->addError('password', $identity->errorCode);
                }
            }
        }
        $this->cats = Categories::model()->parentCats()->findAll();
        $this->render("login", array('form' => $form, 'model' => $model));
    }


    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }


    public function actionRegistration() {
        $model = new User;
        $model->scenario = User::SCENARIO_REGISTRATION;

        $form = new BaseForm('users.RegistrationForm', $model);
        $this->performAjaxValidation($model);
        unset($form->elements['captcha']);

        $is_created = false;

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];

            if ($model->validate()) {
                $password = $model->password;

                $model->password = md5($model->password);
                $model->activate_code = md5($model->password . 'xdf5sf');
                $model->save(false);

                $assignment = new AuthAssignment();
                $assignment->itemname = 'user';
                $assignment->userid = $model->id;
                $assignment->save();
                $this->saveEmailToNewUser($model, $password, $model->activate_code);
                $is_created = true;
            }
        }
        $this->cats = Categories::model()->parentCats()->findAll();
        $this->render(
            'registration',
            array(
                'form' => $form,
                'model' => $model,
                'is_created' => $is_created
            )
        );
    }


    public function actionActivateAccount($code, $login) {
        $this->cats = Categories::model()->parentCats()->findAll();
        $user = User::model()->findByAttributes(array('activate_code' => $code, 'email' => $login));

        if ($user===null) 
            $activate_error = 'Неверные данные активации аккаунта!';
		else {
            if (strtotime($user->date_create) + 24 * 3600 > time()) {
                $user->activate_date = null;
                $user->activate_code = null;
                $user->status = User::STATUS_ACTIVE;
                $user->save(false);

                $message = 'Ваш аккаунт был успешно подтвержден. Хотите ' . CHtml::link('авторизироваться', '#', array('onclick' => '$("#login_box_display").click();')) . '?';
            } else
                $activate_error = 'С момента регистрации прошло больше суток!';
        } 
		
        $this->render('activateAccount', array(
            'activate_error' => isset($activate_error) ? $activate_error : null,
            'message' => isset($message) ? $message : null
        ));
    }


    public function actionActivateAccountRequest() {
        $model = new User(User::SCENARIO_ACTIVATE_REQUEST);

        $form = new BaseForm('users.ActivateRequestForm', $model);

        if ($form->submitted('submit')) {
            $model = $form->model;
            if ($model->validate()) {
                $user = $model->findByAttributes(array('login' => $_POST['User']['login']));

                if (!$user) {
                    $error = UserIdentity::ERROR_UNKNOWN;
                } else {
                    switch ($user->status) {
//                        case User::STATUS_NEW:
//                            $user->generateActivateCode();
//                            $user->save();
//                            $user->sendActivationMail();
//
//                            Yii::app()->user->setFlash('done', 'На ваш Email отправлено письмо с дальнейшими инструкциями.');
//
//                            $this->redirect($this->url('/activateAccountRequest'));
//                            break;

                        case User::STATUS_ACTIVE:
                            $error = UserIdentity::ERROR_ALREADY_ACTIVE;
                            break;

                        case User::STATUS_BLOCKED:
                            $error = UserIdentity::ERROR_BLOCKED;
                            break;
                    }
                }
            }
        }

        $this->layout = '//layouts/login';

        $this->render('activateAccountRequest', array(
            'form' => $form,
            'error' => isset($error) ? $error : null
        ));
    }


    public function actionChangePasswordRequest() {
        Setting::model()->checkRequired(array(
            User::SETTING_CHANGE_PASSWORD_REQUEST_MAIL_SUBJECT,
            User::SETTING_CHANGE_PASSWORD_REQUEST_MAIL_BODY
        ));

        $model = User::model();
        $model->scenario = User::SCENARIO_CHANGE_PASSWORD_REQUEST;

        if (isset($_POST['email'])) {
            $model->attributes = array('email' => $_POST['email']);
            $password_change_code = md5($model->email . time());

            if ($users = User::model()->findAllByAttributes(array('email' => $_POST['email']))) {
                $user = $users[0];
                $body = $change_password = '';
                $settings = Setting::model()->findCodesValues();
                foreach ($users as $account) {
                    if ($user->status == User::STATUS_ACTIVE) {
                        $account->password_change_code = $password_change_code;
                        $account->password_change_date = new CDbExpression('NOW()');
                        $account->save(false);

                        $change_password_url = $this->createAbsoluteUrl('changePassword', array(
                            'code' => $account->password_change_code,
                            'login' => $account->login
                        ));

                        if (count($users) == 1) {
                            $login = $account->login;
                            $change_password .= "<a href='{$change_password_url}' target='_blank'>восстановить</a> ({$change_password_url}) <br/> <br/>";
                        } else {
                            $login = '';
                            $change_password .= "{$account->login} - <a href='{$change_password_url}' target='_blank'>восстановить</a> ({$change_password_url}) <br/> <br/>";
                        }
                    } else {
                        $error = UserIdentity::ERROR_BLOCKED;
                    }
                }
                $mailer_letter = MailerLetter::model();

                $subject = $mailer_letter->compileText($settings[User::SETTING_CHANGE_PASSWORD_REQUEST_MAIL_SUBJECT], array(
                    'user' => $user,
                    'gender' => $user->gender == User::GENDER_MAN ? 'Уважаемый' : 'Уважаемая',
                ), false);
                if (count($users) == 1) {
                    $tmpl = $settings[User::SETTING_CHANGE_PASSWORD_REQUEST_MAIL_BODY_SINGLE];
                } else {
                    $tmpl = $settings[User::SETTING_CHANGE_PASSWORD_REQUEST_MAIL_BODY];
                }
                $body = $mailer_letter->compileText($tmpl, array(
                    'user' => $user,
                    'change_password' => $change_password,
                    'login' => $login
                ));

                $email = $user->email;
                if (Yii::app()->user->role == AuthItem::ROLE_ROOT) {
                    if (isset($_POST['other_email'])) {
                        $email = $_POST['other_email'];
                    }
                }
                MailerModule::sendMail($email, $subject, $body);

                echo CJSON::encode(array(
                    'done' => true,
                    'msg' => 'Данные с восстановлением пароля были высланы Вам на почту.'
                ));
                Yii::App()->end();
            } else {
                $error = "Данный e-mail адрес не был найден. Пожалуйста, уточните e-mail адрес или свяжитесь с <a href='/feedback'>Администратором</a>";
            }


            echo CJSON::encode(array('errors' => array($error)));
        }
    }


    public function actionChangePassword($code, $login) {
        $model = new User(User::SCENARIO_CHANGE_PASSWORD);
        $form = new BaseForm('users.ChangePasswordForm', $model);

        $users = User::model()->findAllByAttributes(array('password_change_code' => $code));
        $user = null;
        foreach ($users as $item) {
            if ($item->login == urldecode($login)) {
                $user = $item;
                break;
            }
        }

        if (!$user) {
            $error = 'Неверная ссылка изменения пароля!';
        } else {
            if (strtotime($user->password_change_date) + 2 * 24 * 3600 > time()) {
                if (isset($_POST['User'])) {
                    $model->attributes = $_POST['User'];
                    if ($model->validate()) {
                        $user->password = md5($_POST['User']['password']);

                        $user->save(false);

                        foreach (User::model()->findAllByAttributes(array('email' => $user->email)) as $model) {
                            $model->password_change_code = null;
                            $model->password_change_date = null;
                            $model->save(false);
                        }

                        Yii::app()->user->setFlash('change_password_done', 'Ваш пароль успешно изменен, вы можете авторизоваться!');

                        $this->redirect($this->url('/users/user/login'));
                    }
                }
            } else {
                $error = 'С момента запроса на восстановление пароля прошло больше суток!';
            }
        }
        $this->layout = '//layouts/login';
        $this->render('changePassword', array(
            'model' => $model,
            'form' => $form,
            'error' => isset($error) ? $error : null
        ));
    }

    private function saveEmailToNewUser($user, $password, $activateCode) {
        $email = Yii::app()->email;
        $email->to = $user->email;
        $email->from = Setting::getValue('support_email');
        $email->subject = 'Активация аккаунта';
        $email->message = Yii::app()->controller->renderInternal(Yii::getPathOfAlias('application.views.yii-mail.activate') . '.php', array('activateCode' => $activateCode, 'user' => $user, 'password' => $password), true);
        $email->send();
    }

    public function actionRecoverPassword() {
        //$this->performAjaxValidation($model);
        /*
                print_r($form);
                die();
        */
        if (isset($_POST['LoginForm'])) {
            $user = User::model()->findByAttributes(array('email' => $_POST['LoginForm']['email']));
            if ($user !== null) {
                $model = $user;
                $model->scenario = User::SCENARIO_RECOVER_PASSWORD;
                if ($model->validate()) {
                    $password = PasswordGenerator::generate(7);
                    $model->password = md5($password);
                    $model->password_c = md5($password);
                    if ($model->save()) {
                        Yii::app()->user->setFlash('flash', 'Пароль для пользователя <b>' . $model->name . '</b> был изменён.');

                        $email = Yii::app()->email;
                        $email->to = $_POST['LoginForm']['email'];
                        $email->from = Setting::getValue('support_email');
                        $email->subject = 'Восстановление пароля';
                        $email->message = Yii::app()->controller->renderInternal(Yii::getPathOfAlias('application.views.yii-mail.pass') . '.php', array('password' => $password, 'fio' => $user->fio), true);
                        $email->send();
                        $this->redirect('/');
                    }
                }
                var_dump($model->getErrors());
            }

        } else {
            $this->redirect('/');
        }
    }

    public function actionUpdateProfile() {
        $saved = Yii::app()->request->getParam('saved', null);
        $model = User::model()->findByPk(Yii::app()->user->id);
        if ($model === null) {
            throw new CHttpException(404);
        }
        $model->scenario = User::SCENARIO_UPDATE;
        $model->password = '';

        $form = new BaseForm('users.RegistrationForm', $model);
        $this->performAjaxValidation($model);
        unset($form->elements['captcha']);

        if (isset($_POST['User'])) {
            if (isset($_POST['User']['password']) && !empty($_POST['User']['password'])) {
                $model->setScenario(User::SCENARIO_CHANGE_PASSWORD);
            }
            $model->attributes = $_POST['User'];

            if ($model->validate()) {
                if ($model->scenario == User::SCENARIO_CHANGE_PASSWORD) {
                    $model->password = md5($model->password);
                }
                $model->save(false);
                $this->redirect('/cabinet/profile/saved');
            }
        }
        $this->cats = Categories::model()->parentCats()->findAll();
        $this->render('profile', array('form' => $form, 'model' => $model, 'saved' => !is_null($saved)));
    }
}
