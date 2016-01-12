<?php
namespace common\modules\rbac\controllers;

use common\modules\rbac\models\AuthItem;
use common\modules\rbac\models\AuthItemSearch;

class OperationAdminController extends \common\components\AdminController
{
    public static function actionsTitles()
    {
        return array(
            "AddAllOperations"            => "Добавление всех операций модулей",
            "Create"                      => "Добавление операции",
            "Update"                      => "Редактирование операции",
            "View"                        => "Просмотр операции",
            "Manage"                      => "Управление операциями",
            "Delete"                      => "Удаление операции",
            "GetModules"                  => "Получение модулей, JSON",
            "GetModuleActions"            => "Получение операции модуля, JSON",
            "RebuildTaskAndOperations"    => "Добавить все модули со всеми экшнами",
        );
    }


    public function actionRebuildTaskAndOperations()
    {
        $modules     = AppManager::getModulesData();
        $modules_arr = array();
        foreach ($modules as $class => $data)
        {
            $modules_arr[$class] = AppManager::getModuleActions($class, true);
        }

        $manager = Yii::app()->authManager;
        $roles   = $manager->getRoles();
        foreach ($modules_arr as $module => $actions)
        {
            $m          = $modules[$module];
            $task1_name = 'Admin_' . ucfirst($m['dir']);
            $task2_name = 'View_' . ucfirst($m['dir']);
            $task_descr = $m['name'];

            //add tasks
            if (!$task1 = $manager->getAuthItem($task1_name))
            {
                $task1 = $manager->createTask($task1_name, $task_descr);
            }
            foreach ($task1->getChildren() as $item)
            {
                $manager->removeItemChild($task1->name, $item->name);
            }

            if (!$task2 = $manager->getAuthItem($task2_name))
            {
                $task2 = $manager->createTask($task2_name, $task_descr);
            }
            foreach ($task2->getChildren() as $item)
            {
                $manager->removeItemChild($task2->name, $item->name);
            }

            //add operations
            foreach ($actions as $action => $descr)
            {
                if (!$manager->getAuthItem($action))
                {
                    $manager->createOperation($action, $descr);
                }

                $task = strpos($action, 'Admin') === false ? $task2 : $task1;

                if (!$task->hasChild($action))
                {
                    $task->addChild($action);
                }
            }

            $task1->addChild($task2_name);

            foreach ($roles as $role)
            {
                if (!$role->hasChild($task2_name))
                {
                    $role->addChild($task2_name);
                }
            }
        }


        foreach (AuthItem::model()->findAllByAttributes(array(
            'type' => AuthItem::TYPE_TASK
        )) as $item)
        {
            $item->bizrule = 'return RbacModule::checkModule($params);';
            $item->bizrule = 'return RbacModule::checkModule($params);';
            $item->save(false);
        }

        //some allow_for_all
        $cr = new CDbCriteria();
        $cr->compare('name', 'FileManager', true);
        foreach (AuthItem::model()->findAll($cr) as $model)
        {
            $model->allow_for_all = 1;
            $model->save(false);
        }
    }


    public function actionAddAllOperations()
    {
        if (isset($_POST["actions"]))
        {
            foreach ($_POST["actions"] as $i => $action)
            {
                Yii::app()->authManager->createOperation($action, $_POST["description"][$i]);
            }

            $this->redirect("AddAllOperations");
        }

        $actions = array();

        $modules = AppManager::getModulesData(true);
        foreach ($modules as $class => $data)
        {
            $actions = array_merge($actions, AppManager::getModuleActions($class, true));
        }

        $actions_names = array_keys($actions);
        $items         = AuthItem::model()->findAllByAttributes(array("type" => AuthItem::TYPE_OPERATION));
        foreach ($items as $item)
        {
            if (in_array($item->name, $actions_names))
            {
                unset($actions[$item->name]);
            }
        }

        $this->render('addAllOperations', array('actions' => $actions));
    }


    public function actionCreate()
    {
        $model = new AuthItem();

        $form = new BaseForm('rbac.OperationForm', $model);

        if (isset($_POST['AuthItem']))
        {
            $model->attributes = $_POST['AuthItem'];
            if ($model->validate())
            {
                Yii::app()->authManager->createOperation($model->name, $model->description, $model->bizrule, $model->data);

                $this->redirect(array(
                    "view",
                    'id' => $model->name
                ));
            }
        }

        $modules = AuthItem::model()->getModulesWithActions();

        $this->render('create', array(
            'modules' => $modules,
            'form'    => $form
        ));
    }


    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $operation = Yii::app()->authManager->getAuthItem($model->name);
        if (!$operation)
        {
            $this->pageNotFound();
        }

        $form = new BaseForm('rbac.OperationForm', $model);

        if (isset($_POST['AuthItem']))
        {
            $model->attributes = $_POST['AuthItem'];
            if ($model->save())
            {
                $this->redirect(array("manage"));
            }
        }

        $this->render('update', array('form' => $form));
    }


    public function actionView($id)
    {
        $model = $this->loadModel($id);

        $this->render('view', array('model' => $model));
    }


    public function actionManage()
    {
        $search = new AuthItemSearch(['scenario' => 'search']);
        $model = $search->search($_GET);
		//$model->unsetAttributes();

        if (isset($_GET['AuthItem']))
        {
            $model->attributes = $_GET['AuthItem'];
        }

        return $this->render('manage', array('model' => $model));
    }


    public function actionDelete($id)
    {
        Yii::app()->authManager->removeAuthItem($id);

        if (!isset($_GET['ajax']))
        {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
    }


    public function loadModel($id)
    {
        $model = AuthItem::model()->findByPk($id);
        if (!$model)
        {
            $this->pageNotFound();
        }

        return $model;
    }


    public function actionGetModules()
    {
        echo CJSON::encode(AppManager::getModulesData(true));
    }


    public function actionGetModuleActions()
    {
        $items   = AuthItem::model()->findAllByAttributes(array('type' => AuthItem::TYPE_OPERATION));
        $actions = AppManager::getModuleActions($_GET['class']);

        foreach ($items as $item)
        {
            if (isset($actions[$item->name]))
            {
                unset($actions[$item->name]);
            }
        }

        echo CJSON::encode($actions);
    }
}



