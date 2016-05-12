<?php
namespace common\modules\users\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use himiklab\sortablegrid\SortableGridAction;

use common\modules\users\models\User;
use common\models\LoginForm;
use common\modules\users\forms\UserForm;
use common\modules\rbac\models\AuthAssignment;

class UserAdminController extends \common\components\AdminController
{
    public static function actionsTitles()
    {
        return array(
            "Manage"          => !empty($_GET['is_deleted'])?"Удаленные пользователи":"Все пользователи",
            "View"            => "Просмотр пользователя",
            "Create"          => "Добавление пользователя",
            "Update"          => "Редактирование пользователя",
            "Delete"          => "Безвозвратное удаление пользователя",
            "SendNewPassword" => "Безвозвратное удаление пользователя",
            "SetDeletedFlag"  => "Удаление и восстановление пользователя",
			"Sort"			  => "",
            "Block"           => "",
        );
    }

    public function actions()
    {
        return [
            'sort' => [
                'class' => \himiklab\sortablegrid\SortableGridAction::className(),
                'modelName' => \common\modules\users\models\User::className(),
            ],
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

    public function actionBlock($id) 
    {
        $model = User::find()->where(['id'=>$id])->one();
        if(empty($model)) $error;

        $model->status = 'blocked';
        $model->save(false);
        $this->redirect(['/rbac/role-admin/manage']);
    }

    public function actionManage($is_deleted = 0)
    {
        $model = new \common\modules\users\models\User;
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

    public function actionCreate()
    {
        $model           = new User;
        $model->scenario = User::SCENARIO_CREATE;
        $model->status = "active";
        
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
                $model->sendPassword();

                $model->password = \Yii::$app->security->generatePasswordHash($model->password);
                $model->activate_code=\Yii::$app->security->generatePasswordHash($model->password.'xdf5sf');
                
                $model->save(false);


                return $this->redirect(array(
                    '/rbac/role-admin/manage',
                ));
            }
        }
        
        $form = new \common\components\BaseForm('/common/modules/users/forms/UserForm', $model);

        return $this->render('create', [
            'form' => $form->out, 
            'model' => $model
        ]);
    }


    public function actionUpdate($id)
    {
        $model             = $this->loadModel($id);
        $old_password = $model->password;
        $model->password_c = $model->password = null;
        $model->scenario   = User::SCENARIO_UPDATE;

		
		\yii::$app->controller->page_title = 'Редактирование пользователя <small>' . $model->name.'</small>';
		\yii::$app->controller->tabs = [
			"управление пользователями" => \yii\helpers\Url::toRoute("manage"),
		];
		\yii::$app->controller->breadcrumbs = [
			['Все пользователи' => '/users/user-admin/manage'],
			$model->name,
		];

        if($model->load(Yii::$app->request->post()))
        {
            if($model->password)
            {
                if($model->send_email)
                {
                    $model->sendPassword();
                }
            
                $model->password = $model->password_c = \Yii::$app->security->generatePasswordHash($model->password);
            }
            else
            {
                $model->password = $model->password_c = $old_password;
            }

            if($model->save())
            {
                return $this->redirect(array(
                    '/rbac/role-admin/manage'
                ));
            }
        }

        $form = new \common\components\BaseForm('/common/modules/users/forms/UserForm', $model);
        return $this->render('update', array(
            'form'  => $form->out,
            'model' => $model,
        ));
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['/rbac/role-admin/manage']);
    }

    public function actionSetDeletedFlag($id, $is_deleted)
    {
        $model              = $this->loadModel($id);
        $model->scenario    = User::SCENARIO_DELETE;
        $model->is_deleted  = $is_deleted;
        $model->date_delete = new CDbExpression('NOW()');
        $model->save(false);

	    return $this->redirect($this->createUrl('manage'));
    }

    /**
     * Finds the Faq model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Faq the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}