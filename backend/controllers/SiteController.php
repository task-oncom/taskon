<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

use common\models\LoginForm;
use common\models\RecoveryForm;
use common\models\ResetPasswordForm;
use common\modules\users\models\User;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $page_title = 'SiteController';
	
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
                        'actions' => ['login', 'recovery', 'reset-password', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->page_title = 'Просмотр главной страницы';
        return $this->render('index');
    }

    public function actionError()
    {
        $this->layout = "clear";

        $exception = Yii::$app->errorHandler->exception;

        return $this->render('error', ['exception' => $exception]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) 
        {
            return $this->goHome();
        }

        $this->page_title = 'Панель управления';
		$this->layout = "blank";
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) 
        {
            if($model->user->role == User::ROLE_ADMIN)
            {
                $model->login();
                return $this->goBack();
            }
            else
            {
                $model->addError('password', 'Вы не имеете доступ в этот раздел');
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionRecovery()
    {
        if (!Yii::$app->user->isGuest) 
        {
            return $this->goHome();
        }

        $this->page_title = 'Востановление пароля';
        $this->layout = "blank";

        $success = false;

        $model = new RecoveryForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) 
        {
            $model->recovery();

            $success = true;
        }

        return $this->render('recovery', [
            'model' => $model,
            'success' => $success,
        ]);
    }

    public function actionResetPassword($token)
    {
        if (!Yii::$app->user->isGuest) 
        {
            return $this->goHome();
        }
        
        $this->page_title = 'Востановление пароля';
        $this->layout = "blank";

        $success = false;
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) 
        {
            $success = true;
        }

        return $this->render('reset-password', [
            'model' => $model,
            'success' => $success,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
