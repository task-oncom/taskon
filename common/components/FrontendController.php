<?php
namespace common\components;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

abstract class FrontendController extends Controller
{
    public $layout = '//main';
    public $page_title;
    public $page_description;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;
    public $crumbs = array();
    public $breadcrumbs = array();

    public function init()
    {
        parent::init();

        $this->_initSession();
    }

    private function _initSession()
    {
        $request = Yii::$app->request;
        if($request->isGet && !$request->isAjax)
        {
            Yii::$app->session->set('SessionData', [$request->url, $request->referrer]);
        }
    }
}
