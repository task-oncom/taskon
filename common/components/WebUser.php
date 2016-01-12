<?php

class WebUser extends CWebUser
{
    public $_model = null;

    public $_role;


    public function getRole()
    {
	$WebUser = new WebUser;
        if ($WebUser->_role == null)
        {
             if($user = $WebUser->getModel())
             {
               $WebUser->_role = $user->role->name;
             }
             else
             {
                $WebUser->_role = AuthItem::ROLE_GUEST;
            }
        }

        return $WebUser->_role;
    }


    public static function setRole($role)
    {
        self::$_role = $role;
    }


    public function isRootRole()
    {
        if($user = $this->getModel())
        {
            return $user->isRootRole();
        }
    }


    public function getName()
    {
        if ($user = $this->getModel())
        {
            return $user->fio;
        }
    }
    public function getLogin()
    {
        if ($user = $this->getModel())
        {
            return $user->email;
        }
    }


    private function getModel()
    {
        if (!Yii::app()->user->isGuest && $this->_model === null)
        {
            $this->_model = User::model()->findByPk($this->id);
        }

        return $this->_model;
    }
}
