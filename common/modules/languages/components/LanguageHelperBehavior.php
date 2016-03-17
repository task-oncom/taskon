<?php
namespace common\modules\languages\components;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

use common\modules\languages\models\Languages;

class LanguageHelperBehavior extends Behavior
{
    private $_langs;

    public $langClass;

    public $field;
    public $langField = 'lang_id';

    public $actions = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_INIT => 'eventInit',
            ActiveRecord::EVENT_AFTER_FIND => 'eventFind',
            ActiveRecord::EVENT_AFTER_UPDATE => 'Save',
            ActiveRecord::EVENT_AFTER_INSERT => 'Insert',
            ActiveRecord::EVENT_BEFORE_DELETE => 'Delete',
        ];
    }

    public function getLangsHelper()
    {
        return $this->_langs;
    }

    private function getShotNameClass()
    {
        return (new \ReflectionClass($this->langClass))->getShortName();
    }

    public function eventInit($event)
    {
        if(in_array(Yii::$app->controller->action->id, $this->actions))
        {
            $langs = Languages::find()->all();

            $field = $this->langField;

            foreach ($langs as $lang) 
            {
                $lng = new $this->langClass;
                $lng->$field = $lang->id;
                $this->_langs[$lang->id] = $lng;
            }
        }
    }

    public function eventFind($event)
    {
        if(in_array(Yii::$app->controller->action->id, $this->actions))
        {
            $langs = Languages::find()->all();

            $field = $this->langField;

            foreach ($langs as $lang) 
            {
                $lng = $this->owner->getLang($lang->id)->one();

                if(!$lng)
                {
                    $lng = new $this->langClass;
                    $lng->$field = $lang->id;
                }      

                $this->_langs[$lang->id] = $lng;
            }
        }
    }

    public function Save($event)
    {
        $langs = Yii::$app->request->post($this->getShotNameClass());
        if ($langs)
        {
            foreach ($langs as $lang_id => $attributes) 
            {
                $class = $this->langClass;

                $lang = $class::find()->where([
                    $this->field => $this->owner->id,
                    $this->langField => $lang_id,
                ])->one();

                if (!$lang)
                {
                    $lang = new $class;
                }

                $attributes[$this->field] = $this->owner->id;
                $attributes[$this->langField] = $lang_id;

                $lang->setAttributes( $attributes );
                if(!$lang->save()) die(print_r($lang->errors));
            }
        }

        return true;
    }

    public function Insert($event)
    {
        $langs = Yii::$app->request->post($this->getShotNameClass());
        if ($langs)
        {
            foreach ($langs as $lang_id => $attributes) 
            {
                $class = $this->langClass;

                $lang = new $class;

                $attributes[$this->field] = $this->owner->id;
                $attributes[$this->langField] = $lang_id;

                $lang->setAttributes($attributes);
                $lang->save(false);
            }
        }

        return true;
    }

    public function Delete($event)
    {
        if(isset($this->owner->langs))
        {
            foreach ($this->owner->langs as $lang) 
            {
                $lang->delete();
            }
        }

        return true;
    }
}
