<?php
namespace common\components\activeRecordBehaviors;
use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use \common\models\MetaTags;

class MetaTagBehavior extends Behavior
{

    public $language = 'ru';

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'Save',
            ActiveRecord::EVENT_AFTER_INSERT => 'Insert',
            ActiveRecord::EVENT_AFTER_DELETE => 'Delete',
        ];
    }

    public function Save($event)
    {
        $attributes = \Yii::$app->request->post('MetaTags');
        if ($attributes)
        {
            $meta_tag = MetaTags::find()->where([
                'object_id' => $this->owner->id,
                'model_id'  => get_class($this->owner),
                'language' => $this->language,
            ])->one();

            if (!$meta_tag)
            {
                $meta_tag = new MetaTags;
            }

            $attributes['object_id'] = $this->owner->id;
            $attributes['model_id']  = get_class($this->owner);
            $attributes['language'] = $this->language;

            $meta_tag->setAttributes( $attributes );
            if(!$meta_tag->save()) die(print_r($meta_tag->errors));
        }

        return true;
    }

    public function Insert($event)
    {
        $attributes = \Yii::$app->request->post('MetaTags');
        if ($attributes )
        {
            $meta_tag = new MetaTags;

            $attributes['object_id'] = $this->owner->id;
            $attributes['model_id']  = get_class($this->owner);
            $attributes['language'] = 'ru';

                $meta_tag->setAttributes( $attributes );
            $meta_tag->save(false);
        }

        return true;
    }

    public function Delete($event)
    {

        $model = MetaTags::find()
            ->where([
                'object_id' => $this->owner->id,
                'model_id'  => get_class($this->owner)
            ])->one();
         if(!empty($model)) $model->delete();

        return true;
    }





}
