<?php

class AuthObjects extends InputWidget
{
    public function run()
    {
        $this->render('AuthObjects', array(
            'roles'        => AuthItem::model()->getRoles(),
            'actions'      => AuthObject::$actions,
        ));
    }
}
