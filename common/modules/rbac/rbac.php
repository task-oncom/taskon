<?php
namespace common\modules\rbac;

class rbac extends \common\components\WebModule
{
    public static $active = true;
	
	public $menu_icons = 'fa fa-lock';


    public static function name()
    {
        return 'Управление доступом';
    }


    public static function description()
    {
        return 'Данный модуль позволяет создавать и редактировать пользователей системы. Для каждого пользователя возможно настроить права доступа к модулем системы. Для создания нового пользователя нажмите кнопку «Создать нового пользователя».';
    }


    public static function version()
    {
        return '1.0';
    }


    public function init()
    {
        parent::init();
		/*$this->setImport(array(
            'rbac.models.*', 'rbac.controllers.*', 'rbac.components.*',
        ));*/
    }


    /*public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action))
        {
            return true;
        }
        else
        {
            return false;
        }
    }*/


    public static function adminMenu()
    {
        return array(
            'Список пользователей'  => '/rbac/role-admin/manage',
            /*'Назначение групп'      => '/rbac/role-admin/assignment',
            'Добавить группу'       => '/rbac/role-admin/create',
            'Операции'              => '/rbac/operation-admin/manage',
            'Добавить операцию'     => '/rbac/operation-admin/create',
            'Задачи'                => '/rbac/task-admin/manage',
            'Добавить задачу'       => '/rbac/task-admin/create'*/
        );
    }


    /**
     * Проверяет резрешено ли $item_name для данной роли
     *
     * @static
     *
     * @param $role
     * @param $item_name
     *
     * @return mixed
     */
    public static function isItemAllow($role, $item_name)
    {
        $role = Yii::app()->authManager->getAuthItem($role);
        return $role->checkAccess($item_name);
    }


    /***
     * Проверяет разрешен ли объект для заданной роли.
     *
     * @static
     *
     * @param $role
     * @param $model_id
     * @param $object_id
     * @param $action View|Admin
     *
     * @return bool
     */
    public static function isObjectItemAllow($role, $model_id, $object_id, $action)
    {
        if ($role == AuthItem::ROLE_ROOT)
        {
            return true;
        }
        $action = $action == 'Admin' ? 'Edit' : $action;

        return (boolean)AuthObject::model()->findByAttributes(array(
            'role'     => $role,
            'model_id' => $model_id,
            'object_id'=> $object_id,
            'action'   => $action
        ));
    }


    public static function isObjectAllow($model_id, $object_id, $action)
    {
        return self::isObjectItemAllow(Yii::app()->user->role, $model_id, $object_id, $action);
    }


    public static $cash;


    /**
     * Эта функция вызывается из безнесправил задач.
     * Для инициализации базы используйте /rbac/operationAdmin/rebuildTaskAndOperations
     *
     * @static
     * @param $params
     * @return array|bool
     */
    public static function checkModule($params)
    {
        if (!isset($params['item']))
        {
            return true;
        }
        if ((($action = substr($params['item'], 0, 4)) != 'View') &&
            (($action = substr($params['item'], 0, 5)) != 'Admin')
        )
        {
            return false;
        }
        $module = lcfirst(str_replace($action . '_', '', $params['item']));
        $module = Yii::app()->getModule($module);

        if (isset(self::$cash[$params['item']][$action]))
        {
            return self::$cash[$params['item']][$action];
        }

        if (method_exists($module, 'getOperations'))
        {
            foreach ($module->getOperations() as $data)
            {
                if (Yii::app()->user->checkAccess($data, $action))
                {
                    return self::$cash[$params['item']][$action] = true;
                }
            }
        }
        else
        {
            return self::$cash[$params['item']][$action] = true;
        }
        return false;
    }


    public static function initRbacFromArray($array)
    {
        $auth = Yii::app()->authManager;

        //is_string
        if (is_string($array))
        {
            if (($item = $auth->getAuthItem($array)) == null)
            {
                self::createAuthItem($array);
            }
            return;
        }

        //is_array
        foreach ($array as $key => $info)
        {
            $item_is_parent = is_numeric($key);
            $item_name      = $item_is_parent ? $info : $key;

            if (($item = $auth->getAuthItem($item_name)) == null)
            {
                $item = self::createAuthItem($item_name, $info);
            }

            if ($item_is_parent)
            {
                if (is_array($info))
                {
                    $children = isset($info['children']) ? $info['children'] : array();
                }
                else
                {
                    $children = $info;
                }
                self::initRbacFromArray($children);
                foreach ((array)$children as $child)
                {
                    if (!$item->hasChild($child))
                    {
                        $item->addChild($child);
                    }
                }
            }
        }
    }


    public static function createAuthItem($item_name, $data = array())
    {
        $default = array(
            'type'        => CAuthItem::TYPE_OPERATION,
            'description' => '',
            'bizRule'     => "",
            'data'        => array()
        );
        $tmp     = is_array($data) ? CMap::mergeArray($default, $data) : $default;

        return Yii::app()->authManager->createAuthItem($item_name, $tmp['type'], $tmp['description'], $tmp['bizRule'], $tmp['data']);
    }


}
