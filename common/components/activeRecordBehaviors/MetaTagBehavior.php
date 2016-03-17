<?php
namespace common\components\activeRecordBehaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

use \common\models\MetaTags;
use common\modules\languages\models\Languages;

class MetaTagBehavior extends Behavior
{
    public $meta;

    public $actions = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_INIT => 'eventInit',
            ActiveRecord::EVENT_AFTER_FIND => 'eventFind',
            ActiveRecord::EVENT_AFTER_UPDATE => 'eventSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'eventInsert',
            ActiveRecord::EVENT_BEFORE_DELETE => 'eventDelete',
        ];
    }

    public function eventInit($event)
    {
        if(in_array(Yii::$app->controller->action->id, $this->actions))
        {
            $langs = Languages::find()->all();

            foreach ($langs as $lang) 
            {
                $mt = new MetaTags;
                $mt->lang_id = $lang->id;
                $this->meta[$lang->id] = $mt;
            }
        }
    }

    public function eventFind($event)
    {
        if(in_array(Yii::$app->controller->action->id, $this->actions))
        {
            $langs = Languages::find()->all();

            foreach ($langs as $lang) 
            {
                $mt = $this->owner->getMetaTags($lang->id)->one();

                if(!$mt)
                {
                    $mt = new MetaTags;
                    $mt->lang_id = $lang->id;
                }      

                $this->meta[$lang->id] = $mt;
            }
        }
    }

    public function eventSave($event)
    {
        $meta = \Yii::$app->request->post('MetaTags');
        if ($meta)
        {
            foreach ($meta as $lang_id => $attributes) 
            {
                $meta_tag = MetaTags::find()->where([
                    'object_id' => $this->owner->id,
                    'model_id'  => get_class($this->owner),
                    'lang_id' => $lang_id,
                ])->one();

                if (!$meta_tag)
                {
                    $meta_tag = new MetaTags;
                }

                $attributes['object_id'] = $this->owner->id;
                $attributes['model_id']  = get_class($this->owner);
                $attributes['lang_id'] = $lang_id;

                $meta_tag->setAttributes( $attributes );
                if(!$meta_tag->save()) die(print_r($meta_tag->errors));
            }
        }

        return true;
    }

    public function eventInsert($event)
    {
        $meta = \Yii::$app->request->post('MetaTags');
        if ($meta)
        {
            foreach ($meta as $lang_id => $attributes) 
            {
                $meta_tag = new MetaTags;

                $attributes['object_id'] = $this->owner->id;
                $attributes['model_id']  = get_class($this->owner);
                $attributes['lang_id'] = $lang_id;

                $meta_tag->setAttributes($attributes);
                $meta_tag->save(false);
            }
        }

        return true;
    }

    public function eventDelete($event)
    {
        if($this->owner->metaTags)
        {
            foreach ($this->owner->metaTags as $meta) 
            {
                $meta->delete();
            }
        }
        
        return true;
    }
}
