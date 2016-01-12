<?php
namespace common\components;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\models\MetaTags;

abstract class BaseController extends Controller
{

    public $layout = '//main';
    public $page_title;
	public $page_description;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;
    public $crumbs = array();
	public $breadcrumbs = array();

    public static function actionsTitles(){}

    public function init()
    {
        parent::init();

        $this->_initLanguage();
	    if (YII_DEBUG)
        {
            $this->view->registerJSFile(/*\Yii::$app->basePath . */'/js/plugins/debug.js');
        }
    }


    private function _initLanguage()
    {
        if (isset($_GET['lang']))
        {
            Yii::$app->language = $_GET['lang'];
            Yii::$app->session['language'] = $_GET['lang'];
        }

        if (!isset(Yii::$app->session['language']) || Yii::$app->session['language'] != Yii::$app->language)
        {
            Yii::$app->session['language'] = Yii::$app->language;
        }
    }

    public function beforeAction($action)
    {

        /*if(substr($currentUrl, -1) == '/' && $currentUrl!="/") {
        	$urlWithoutSlash = substr($currentUrl, 0, -1);
        	return $this->redirect($urlWithoutSlash,true,301);
        }*/

        $actions = ['/login', '/changePasswordRequest', '/changePassword', '/error'];

        $curr_action = lcfirst($action->id);

        $feedback_action = $action->controller->id == 'feedback' && $curr_action == 'create';

		$testing_controller = $action->controller->id == 'testingTest';


        if (!empty(Yii::$app->request->cookies['startPage']->value)) {
			Yii::$app->response->cookies->add(new \yii\web\Cookie([
				'name' => 'startPage',
				'value' => "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"
			]));
        }


        if (Yii::$app->user->isGuest && !in_array($curr_action, $actions) && !$feedback_action && !$testing_controller)
        {
            //$this->redirect($this->url('/users/user/login'));
        }

	 /*$item_name = common\modules\rbac\models\AuthItem::constructName(Yii::$app->controller->id, $action->id);
	
        if (isset(Yii::$app->params->save_site_actions) && Yii::$app->params->save_site_actions)
        {
            common\modules\main\MainModule::saveSiteAction();
        }*/

        $this->setTitle($action);
        $this->_setMetaTags($action);

        return true;
    }


    private function _setMetaTags($action)
    {

        if ($action->id != 'view' || $this instanceof AdminController)
        {
            return false;
        }

        $this->request->parsers = ['*'];
        $id = $this->request->getQueryParam("id");
        $url = $this->request->getQueryParam("url");
        $page = $this->request->getQueryParam("page");

        if(empty($page) && empty($url) && empty($id))
            $page = '/';
        if ($id != '')
        {
            $class = $this->getModelClass();

            $meta_tag = MetaTags::findOne([
                'model_id' => $class,
                'object_id' => $id
                    ]);

            if ($meta_tag)
            {
                $this->meta_title = $meta_tag->title;
                $this->meta_keywords = $meta_tag->keywords;
                $this->meta_description = $meta_tag->description;
            }
        } elseif ($url != '' )
        {
            $class = $this->getModelClass();
            if($class == 'Faq')
                $class = '\common\modules\faq\models\Faq';

            $page_model = new $class;
            $page_model = $page_model::findOne(['url' => $url]);
            
            if ($page_model == null)
                return '';
            
            $meta_tag = MetaTags::find()->where([
                'model_id' => $class,
                'object_id' => $page_model->id
                    ])->one();

            if ($meta_tag)
            {
                $this->meta_title = $meta_tag->title;
                $this->meta_keywords = $meta_tag->keywords;
                $this->meta_description = $meta_tag->description;
            }
        }elseif ( $page != '')
        {
            $class = 'common\modules\content\models\CoContent';

            $page_model = new $class;

            $page_model = $page_model::findOne(['url' => $page]);

            if ($page_model == null)
                return '';

            $meta_tag = MetaTags::findOne([
                'model_id' => $class,
                'object_id' => $page_model->id
            ]);

            if ($meta_tag)
            {
                $this->meta_title = $meta_tag->title;
                $this->meta_keywords = $meta_tag->keywords;
                $this->meta_description = $meta_tag->description;
                $this->page_title = $meta_tag->title;
            }
        }
    }


    private function getModelClass()
    {
        return ucfirst(str_replace('-admin', '', $this->id));
    }

    public function checkAccess($item_name)
    {
        //Если суперпользователь, то разрешено все
        if (isset(Yii::$app->user->role) && Yii::$app->user->role == AuthItem::ROLE_ROOT)
        {
            return true;
        }

        $auth_item = AuthItem::model()->findByPk($item_name);

        if (!$auth_item)
        {
            Yii::log('Задача $item_name не найдена!');
            return false;
        }

        if ($auth_item->allow_for_all)
        {
            return true;
        }


        if ($auth_item->task)
        {
            if ($auth_item->task->allow_for_all)
            {
                return true;
            }
            elseif (Yii::$app->user->checkAccess($auth_item->task->name))
            {
                return true;
            }
        }
        else
        {
            if (Yii::$app->user->checkAccess($auth_item->name))
            {
                return true;
            }
        }

        return false;
    }


    public function setTitle($action)
    {
        $action_titles = call_user_func(array(
            get_class($action->controller),
            'actionsTitles'
                ));

        if (!isset($action_titles[ucfirst($action->id)]))
        {
            throw new \yii\web\HttpException('Не найден заголовок для дейсвия ' . ucfirst($action->id));
        }

        $title = $action_titles[ucfirst($action->id)];

        $this->page_title = $title;
    }


    public function url($route, $params = [], $ampersand = '&')
    {
        //$url = $this->createUrl($route, $params, $ampersand);
		$route = \yii\helpers\BaseArrayHelper::merge([$route], $params);
		$url = Url::toRoute($route);
        return $url;
    }


    /**
     * @throws CHttpException
     */
    protected function pageNotFound()
    {   
        throw new CHttpException(404, 'Страница не найдена!');
    }


    /**
     * @throws CHttpException
     */
    protected function forbidden($item_name)
    {
        throw new CHttpException(403, 'Запрещено!'.$item_name);
    }


    public function getRequest()
    {
        return Yii::$app->request;
    }


    public function msg($msg, $type)
    {
        return "<div class='message {$type}' style='display: block;'>
                    <p>{$msg}</p>
                </div>";
    }


    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']))
        {
            echo CActiveForm::validate($model);
            Yii::$app->end();
        }
    }


    /**
     * Возвращает модель по атрибуту и удовлетворяющую скоупам,
     * или выбрасывает 404
     *
     * @param string     $class  имя класса модели
     * @param int|string $value  значение атрибута
     * @param array      $scopes массив скоупов
     * @param string     $attribute
     *
     * @return CActiveRecord
     */
    public function loadModel($value, $scopes = array(), $attribute = null)
    {
        //$model = \yii\db\ActiveRecord::model($this->getModelClass());
		$model = "\common\modules\users\models\\".$this->getModelClass();
//die(print_r($model));
        /*foreach ($scopes as $scope)
        {
            $model->$scope();
        }*/

        if ($attribute === null)
        {
            $model = $model::findOne($value);
        } else
        {
            $model = $model::find()->where([$attribute => $value])->all();
        }

        if ($model === null)
        {
            $this->pageNotFound();
        }

        return $model;
    }


    /**
     * Обертка для Yii::t, выполняет перевод по словарям текущего модуля.
     * Так же перевод осуществляется по словорям с префиксом {modelId},
     * где modelId - приведенная к нижнему регистру база имени контроллера
     *
     * Например: для контроллера ProductInfoAdminController, находящегося в модуле ProductsModule
     * перевод будет осуществляться по словарю ProductsModule.product_info_{первый параметр метода}
     *
     * @param string $dictionary словарь
     * @param string $alias      фраза для перевода
     * @param array  $params
     * @param string $language
     *
     * @return string переводa
     */
    public function t($dictionary, $alias, $params = array(), $source = null, $language = null)
    {
        $file_prefix = StringHelper::camelCaseToUnderscore($this->getModelClass());
        return Yii::t(get_class($this->module) . '.' . $file_prefix . '_' . $dictionary, $alias, $params, $source, $language);
    }


}
