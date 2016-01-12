<?php

namespace common\modules\main\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

//class MainAdminController extends \common\components\AdminController
class MAINAdminController extends Controller
{
    
	public function init() {
		die($this->context->module->id);
	}
	
	public function actionIndex()
    {
        error_reporting(E_ALL);
		ini_set('display_errors', 1);
		
		return $this->render('\common\modules\main\mainAdmin\index');
    }

}
