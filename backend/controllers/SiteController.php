<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

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

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->page_title = 'Панель управления';
		$this->layout = "blank";
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
	
	public function actionLogin1()
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

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
