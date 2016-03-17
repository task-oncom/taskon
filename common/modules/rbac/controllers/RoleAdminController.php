<?php
namespace common\modules\rbac\controllers;

use common\modules\rbac\models\AuthAssignment;
use common\modules\rbac\models\AuthItem;
use common\modules\rbac\models\AuthItemChild;
use common\modules\rbac\models\AuthObject;

class RoleAdminController extends \common\components\AdminController
{
    public static function actionsTitles()
    {
        return array(
            "Create"           	=> "Добавление группы",
            "Update"           	=> "Редактирование группы",
            "View"             	=> "Просмотр группы",
            "Manage"           	=> "Управление группами",
            "Delete"           	=> "Удаление группы",
            "ModuleOperations" 	=> "Разграничение прав",
            "SetAccess"        	=> "Назначение прав",
            "Assignment"       	=> "Назначение групп",
			"Up"				=> 'Начальная установка правил',
			"Changeaccess"		=> '',
			
        );
    }


    public function actionChangeaccess() {
		if(!empty($_GET['user_id']) && !empty($_GET['item']) && !empty($_GET['op'])) 
        {
			$op = $_GET['op'];
			$user_id = $_GET['user_id'];
			$item = $_GET['item'];
            
			$r = \yii::$app->authManager->getPermission($item);

			if($op == 'assign')
            {
				if(!\yii::$app->authManager->checkAccess($user_id, $item)) 
                {
					\yii::$app->authManager->assign($r, $user_id);
					echo 'assigned';
					die();
				}
            }
			else
            {
				if(\yii::$app->authManager->checkAccess($user_id, $item)) 
                {
					\yii::$app->authManager->revoke($r, $user_id);
					echo 'deassigned';
					die();
				}
            }
		}
	}
	
	public function actionCreate()
    {
        $model = new AuthItem();

        $form = new BaseForm('rbac.RoleForm', $model);

        if (isset($_POST['AuthItem']))
        {
            $model->attributes = $_POST['AuthItem'];
            if ($model->validate())
            {
                $role = Yii::app()->authManager->createRole($model->name, $model->description, $model->bizrule, $model->data);

                if (isset($_POST['AuthItem']['parent']) && $_POST['AuthItem']['parent'])
                {
                    Yii::app()->authManager->addItemChild($_POST['AuthItem']['parent'], $model->name);
                }

                $this->redirect($this->redirect($this->createUrl("view", array('id' => $model->name))));
            }
        }

        $this->render('create', array('form' => $form));
    }


    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $this->protectSystemRoles($model);

        $role = Yii::app()->authManager->getAuthItem($model->name);
        if (!$role)
        {
            $this->pageNotFound();
        }

        $form = new BaseForm('rbac.RoleForm', $model);

        if (isset($_POST['AuthItem']))
        {
            $model->attributes = $_POST['AuthItem'];
            if ($model->validate())
            {
                $role->name        = $model->name;
                $role->description = $model->description;
                $role->bizrule     = $model->bizrule;
                $role->data        = $model->data;

                $this->redirect($this->createUrl("manage"));
            }
        }

        $this->render('update', array('form' => $form));
    }


    public function actionView($id)
    {
        $model = $this->loadModel($id);

        $this->render('view', array('model' => $model));
    }

	/*public function actionUp(){
			foreach($rights as $rule) {
			if(!$rbac->getPermission($rule)) {
				$r = $rbac->createPermission($rule);
				$r->description = $rule;
				$rbac->add($r);
				
				$rbac->assign($r, 77);
			}
		}
	}*/
	
    public function actionDown() {
		$manager = \yii::$app->authManager;
		$manager->removeAll();
	}
	
	public function actionManage()
    {
        \yii::$app->controller->page_title = 'Список пользователей';
        \yii::$app->controller->breadcrumbs = [
            '/users/user-admin/create' => 'Управление доступом',
            'Список пользователей',
        ];

        $modules = [];
		$columns = [];

        $columns[] = [
            'label' => 'ФИО',
            'format' => 'raw',
            /*'contentOptions' => ['style'=>'width: 100px;'],*/
            'value' => function($model) {
                return \yii\helpers\Html::a($model->getCustomName(),\yii\helpers\Url::toRoute(['/users/user-admin/update', 'id'=>$model->id]));
            }
        ];
		
		foreach(\common\components\AppManager::getModulesList() as $module_id => $module_name) {

			if($module_id == 'users') continue;

            $arr = [];
			$arr['module_id'] = $module_id;
			$arr['module_name'] = $module_name;

            $r = \yii::$app->authManager->getPermission($module_id);

            if(empty($r)) {
                $r = \yii::$app->authManager->createPermission($module_id);
                $r->description = $module_name;
                \yii::$app->authManager->add($r);

            }
            if(!\yii::$app->authManager->checkAccess(77, $module_id))
                \yii::$app->authManager->assign($r, 77);

            $ret = [
                'label' => $module_name,
                'headerOptions'=>['style'=>'max-width: 100px; height: 50px; white-space: normal;'],
                'format' => 'raw',
                'contentOptions' => ['style'=>'width: 70px;'],
                'contentOptions'=>['style'=>'max-width: 150px; ', 'align' => 'center'],
                'value' => function($model) use ($module_id) {
                    $val = \yii::$app->authManager->checkAccess($model->id, $module_id);
                    return \yii\helpers\Html::checkBox($module_id.'_'.$model->id, $val, [
                        'data-render'=>'switchery',
                        'data-theme'=>'default',
                        'data-classname'=>'switchery',
                        'user-id' => $model->id,
                        'item' => $module_id,
                    ]);
                }
            ];
			$columns[] = $ret;
			$modules[] = $arr;
		}
//die(print_r($columns));
        $modules['columns'] = $columns;

		$searchModel = new \common\modules\users\models\UserSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'modules' => $modules
		]);
    }


    public function actionDelete($id)
    {
        $this->protectSystemRoles($id);

        Yii::app()->authManager->removeAuthItem($id);

        if (!isset($_GET['ajax']))
        {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
    }


    public function actionAssignment()
    {
        $model = new User('search');
        $model->unsetAttributes();
        if (isset($_GET['User']))
        {
            $model->attributes = $_GET['User'];
        }

        if (isset($_POST['user_id']) && isset($_POST['role']))
        {
            AuthAssignment::updateUserRole($_POST['user_id'], $_POST['role']);

            Yii::app()->end();
        }

        $this->render('assignment', array('model' => $model));
    }


    public function loadModel($value, $scopes = [], $attribute = NULL)
    {
        $model = AuthItem::findByPk($value)->one();
        if (!$model)
        {
            $this->pageNotFound();
        }

        return $model;
    }


    private function protectSystemRoles($role)
    {
        if (!is_object($role))
        {
            $role = $this->loadModel($role);
        }

        if (in_array($role->name, AuthItem::$system_roles))
        {
            throw new CException('Нельзя редактировать и удалять системные роли!');
        }
    }


    public function actionSetAccess($role, $item, $allow, $is_object = null)
    {
        if (!Yii::app()->request->isAjaxRequest)
        {
            Yii::app()->end();
        }

        if ($is_object)
        {
            $info            = explode('_', $item);
            $auth_attributes = array(
                'model_id'  => $info[0],
                'object_id' => $info[1],
                'action'    => $info[2],
                'role'      => $role
            );
            $auth_object     = new AuthObject();

            if ($allow)
            {
                $auth_object->attributes = $auth_attributes;
                $auth_object->save();
            }
            else
            {
                $auth_object->deleteAllByAttributes($auth_attributes);
            }
        }
        else
        {
            $auth = Yii::app()->authManager;
            if (strpos($item, 'Edit') > 0)
            {
                $parts = explode('_', $item);
                $item  = 'Admin_' . $parts[0];
            }
            if ($allow)
            {
                $auth->addItemChild($role, $item);
            }
            else
            {
                $auth->removeItemChild($role, $item);
            }
        }
    }


    public function actionModuleOperations($role, $module = null, $is_object = false)
    {
        if (!$module)
        {
            $operations = array();

            foreach (AppManager::getModulesData(true) as $mod)
            {
                $operations[] = array(
                    'op_id' => ucfirst($mod['dir']),
                    'title' => $mod['name']
                );
            }
        }
        else
        {
            $module = Yii::app()->getModule($module);

            if (method_exists($module, 'getOperations'))
            {
                $operations = $module->getOperations();
            }
            else
            {
                throw new CException('Этот модуль не поддерживает разграничение прав');
            }
        }

        if (is_array($operations))
        {
            $dp           = new CArrayDataProvider($operations, array('keyField'=> 'title'));
            $dp->keyField = 'title';

            $dp->pagination = false;
        }
        elseif ($operations instanceof CArrayDataProvider)
        {
            $dp           = $operations;
            $dp->keyField = 'title';
        }
        elseif ($operations instanceof CDataProvider)
        {
            $dp = $operations;
        }
        else
        {
            throw new CException(
                get_class($module) . "::getOperations должен возвращать массив или DataProvider");
        }

        if ($dp instanceof CArrayDataProvider)
        {
            $data = array();
            foreach ($dp->getData() as $key => $val)
            {
                if (is_array($val))
                {
                    $data[] = $val;
                }
                elseif (is_string($val))
                {
                    $data[] = array(
                        'title'  => $key,
                        'op_id'  => $val
                    );
                }
                elseif (is_object($val))
                {
                    $data[] = array(
                        'title'    => $key,
                        'object'   => $val,
                    );
                }
            }

            $dp->setData($data);
        }

        $this->render('moduleOperations', array(
            'dp'     => $dp,
            'role'   => $role,
            'module' => $module
        ));
    }
}
