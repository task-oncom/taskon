<?php

Yii::import('zii.widgets.jui.CJuiWidget');

class Answers extends CJuiWidget
{
    public $model;
    public $id;
    public $title = 'ответы';
    public $options = array();
    public $params = array();
    public $tag = 'answers';

    public function init()
    {
        parent::init();
        $this->initVars();
    }

    public function initVars()
    {
        if ($this->model === null)
            throw new CException('Параметр model является обязательным');

    }

    public function run()
    {
        $this->render('answers', array('model' => $this->model));
    }

}
