<?php
namespace frontend\controllers;

use common\modules\scoring\models\ScRequest;
use Yii;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use common\components\BaseController;
use common\models\Cities;
use yii\helpers\ArrayHelper;
use common\modules\request\models\ScZodiac;
use \yii\web\Response;
use \yii\widgets\ActiveForm;
use common\modules\scoring\models\ScClient;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    public $layout = '//main';

    public static function actionsTitles(){
        return [
            'Index' 		  => 'Главная страница',
            'Error'           => 'Error',
            'Login'            => '',
            'Logout'            => '',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
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

        $content = $model->getContent();
        $this->meta_title = $model->metaTags->title;
        $this->meta_description = $model->metaTags->description;
        $this->meta_keywords = $model->metaTags->keywords;

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
        $this->layout = '//main-short';
        $model = new \frontend\models\LoginForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return  \yii\widgets\ActiveForm::validate($model);
            die();
        }

        if (!\Yii::$app->user->isGuest) {
            if(\Yii::$app->user->identity->active > 3)
                return $this->goHome();
            else
                return $this->redirect(Url::toRoute(['/scoring/register/step', 'step'=>\Yii::$app->user->identity->active]));
        }


        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // if(\Yii::$app->user->identity->active > 3)
            //     return $this->goHome();
            // else {
            //     $step = \Yii::$app->user->identity->active;
            //     if($step < 1) $step = 1;
            //     return $this->redirect(Url::toRoute(['/scoring/register/step', 'step' => $step]));
            // }

            switch (\Yii::$app->user->identity->result) {
                case ScClient::STATUS_STEP_1:
                    return $this->redirect(Url::toRoute(['/scoring/register/step', 'step'=>1]));
                    break;
                case ScClient::STATUS_STEP_2:
                    return $this->redirect(Url::toRoute(['/scoring/register/step', 'step'=>2]));
                    break;
                case ScClient::STATUS_STEP_3:
                    return $this->redirect(Url::toRoute(['/scoring/register/step', 'step'=>3]));
                    break;
                case ScClient::STATUS_CABINET:
                    return $this->redirect(Url::toRoute(['/scoring/clients/cabinet']));
                    break;
                case ScClient::STATUS_PAYDETAIL:
                    $request = ScRequest::find()->where(['user_id' => Yii::$app->user->id, 'status' => ScRequest::STATUS_NEW])->one();
                    if($request)
                    {
                        Yii::$app->session['Pay'] = ScRequest::$namePay[$request->payment];
                        Yii::$app->session['Request_id'] = $request->id;
                        return $this->redirect(Url::toRoute(['/scoring/clients/paydetails']));
                    }
                    else
                    {
                        return $this->redirect(Url::toRoute(['/scoring/clients/cabinet']));
                    }
                    break;
                default:
                    return $this->goHome();
                    break;
            }

        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
