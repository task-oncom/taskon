<?php

namespace common\components;

abstract class WebModule extends \yii\base\Module
{
    public static $active = true;

    public static $base_module = false;
	
	public $menu_icons = '';

    /*public static  function name();

    public static  function description();

    public static  function version();*/

    protected $_assetsUrl;


    public function getMenuIcons() {
		return $this->menu_icons;
	}
	
	public static function name() {
		return '';
	}
	
	public static function description() {
	}
	
	public static function version() {
	}
	
	public function assetsUrl()
    {

        if ($this->_assetsUrl === null)
        {
            $class = str_replace('Module', '', get_class($this));
            $class = lcfirst($class);

            $path = Yii::getPathOfAlias($class . '.assets');

            if ($path)
            {
                $this->_assetsUrl = Yii::app()->getAssetManager()->publish($path);
            }
        }

        return $this->_assetsUrl;
    }


    public static function getShortId()
    {
        return strtolower(str_replace('Module', '', get_called_class()));
    }
}
