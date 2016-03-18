<?php
namespace frontend\controllers;

use Yii;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

use common\components\FrontendController;
use common\models\LoginForm;
use common\modules\users\models\User;
use common\modules\eauth\components\GoogleOAuth2Service;
use common\modules\eauth\models\UserEAuth;

/**
 * Site controller
 */
class SiteController extends FrontendController
{
    public $layout = '//main';

    public static function actionsTitles(){
        return [
            'Index'           => 'Главная страница',
            'Contacts'           => 'Контакты',
            'Error'           => 'Error',
            'Login'            => 'Вход',
            'Logout'            => 'Выход',
        ];
    }

    // TEMP
    public function actionContacts()
    {
        Yii::$app->controller->meta_title = 'Контакты ООО "Арт Проект"';

        return $this->render('contacts');
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'login'],
                'rules' => [
                    [
                        'actions' => ['signup', 'login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'eauth' => [
                // required to disable csrf validation on OpenID requests
                'class' => \nodge\eauth\openid\ControllerBehavior::className(),
                'only' => ['login'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            /*'error' => [
                'class' => 'yii\web\ErrorAction',
            ],*/
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionError()
    {
        $model = \common\modules\content\models\CoContent::findOne(['url' => 'site/error']);

        $content = $model->lang->getFinishedContent();
        $this->meta_title = $model->metaTag->title;
        $this->meta_description = $model->metaTag->description;
        $this->meta_keywords = $model->metaTag->keywords;

        return $this->render('error', ['content'=>$content]);
    }

    public function actionIndex()
    {
        if(!Yii::$app->user->isGuest)
            return $this->redirect(Url::toRoute('/scoring/clients/cabinet'));
        return $this->render('index');
    }

    public function actionLogin()
    {
         $serviceName = Yii::$app->request->getQueryParam('service_eauth');

         if (isset($serviceName)) {
            /** @var $eauth \nodge\eauth\ServiceBase */
            $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);
            $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
            $eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('site/login'));

            if ($serviceName == 'facebook' || $serviceName == 'vk') {
                $eauth->setScope('email');
            }
            else if ($serviceName == 'google') {
                $eauth->setScope(GoogleOAuth2Service::SCOPE_EMAIL);
            }

            try {
                if ($eauth->authenticate()) {
                    $eauth->getAttributes(); // get EAuth info
                    // Добавить проверку обязательных полей - если нет какого-то
                    // обязательного поля, то выводить форму для заполнения
                    $eauth->checkAttributes();

                    $userEAuthModel = new UserEAuth();
                    $identity = $userEAuthModel->getByEAuth($eauth);
                    Yii::$app->getUser()->login($identity);

                    // special redirect with closing popup window
                    $eauth->redirect('/school');
                }
                else {
                    // close popup window and redirect to cancelUrl
                    $eauth->cancel('/school');
                }
            }
            catch (\nodge\eauth\ErrorException $e) {
                echo '<pre>'; die(var_dump($e->getMessage())); echo '</pre>';
                // save error to show it later
                Yii::$app->getSession()->setFlash('error', 'EAuthException: '.$e->getMessage());

                // close popup window and redirect to cancelUrl
                $eauth->cancel('/school');
            }
        }

        //Yii::$app->user->getIdentity()->getRole()
        $model = new LoginForm(); 
        if ($model->load(Yii::$app->request->post()) && $model->validate()) 
        {
            if($model->user->role == User::ROLE_USER)
            {
                $model->login();
            }
            else
            {
                echo json_encode(['errors' => []]);
            }

            $this->redirect(['/support']);
        }
        else 
        {
            echo json_encode(array('errors'=>$model->getErrors()));
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
