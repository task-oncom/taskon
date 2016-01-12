<?php

class AuthObject extends ActiveRecordModel
{
    const PAGE_SIZE = 10;

    const ACTION_VIEW   = 'View';
    const ACTION_UPDATE = 'Edit';


    public static $actions = array(
        self::ACTION_VIEW   => 'просмотр',
        self::ACTION_UPDATE => 'редактирование'
    );


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return 'auth_objects';
    }


    public function name()
    {
        return 'Доступ к объектам';
    }


    public function rules()
    {
        return array(
            array('object_id, model_id, role, action', 'required'), array(
                'object_id', 'length',
                'max' => 11
            ), array(
                'model_id', 'length',
                'max' => 50
            ),

            array(
                'id, object_id, model_id', 'safe',
                'on' => 'search'
            ),
        );
    }


    public function relations()
    {
        return array();
    }


    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('object_id', $this->object_id, true);
        $criteria->compare('model_id', $this->model_id, true);

        return new ActiveDataProvider(get_class($this), array(
            'criteria' => $criteria
        ));
    }


    public function getObjectsIds($model_id, $action, $role)
    {
        $object_ids = array();

        $sql = "SELECT object_id
                       FROM " . $this->tableName() . "
                       WHERE model_id = '{$model_id}' AND
                             action   = '{$action}'   AND
                             role     = '{$role}'";

        $result = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($result as $data)
        {
            $object_ids[] = $data['object_id'];
        }

        return $object_ids;
    }


    public function getRolesNames($model_id, $object_id)
    {
        $roles_names = array();

        if (!$object_id)
        {
            return $roles_names;
        }

        $sql = "SELECT role
                       FROM " . $this->tableName() . "
                       WHERE model_id  = '{$model_id}' AND
                             object_id = '{$object_id}'";

        $result = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($result as $data)
        {
            $roles_names[] = $data['role'];
        }

        return $roles_names;
    }


    public function getRolesDescriptions($model_id, $object_id)
    {
        $roles_descriptions = array();

        if (!$object_id)
        {
            return $roles_descriptions;
        }

        $sql = "SELECT description
                       FROM " . AuthItem::tableName() . "
                       WHERE name IN (
                          SELECT role
                               FROM " . $this->tableName() . "
                               WHERE model_id  = '{$model_id}' AND
                                     object_id = '{$object_id}'
                       )
                ";

        $result = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($result as $data)
        {
            $roles_descriptions[] = $data['description'];
        }

        return $roles_descriptions;
    }


    public function exists($model_id, $object_id, $action = 'view')
    {
        return (boolean)$this->findByAttributes(array(
            'model_id'  => $model_id,
            'object_id' => $object_id,
            'action'    => $action,
            'role'      => Yii::app()->user->role
        ));
    }


    public static function addObjectsAndAllowToAll($objects)
    {
        $tmpl           = new AuthObject;
        $tmpl->model_id = get_class($objects[0]);

        foreach (Yii::app()->authManager->getRoles() as $name => $role)
        {
            foreach ($objects as $item)
            {
                foreach (array('View', 'Edit') as $action)
                {
                    $ao            = clone $tmpl;
                    $ao->object_id = $item->id;
                    $ao->role      = $name;
                    $ao->action    = $action;
                    $ao->save(false);
                }
            }
        }
    }
}