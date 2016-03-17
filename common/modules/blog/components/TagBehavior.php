<?php
namespace common\modules\blog\components;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

use common\modules\blog\models\PostTag;
use common\modules\blog\models\PostTagAssign;

class TagBehavior extends Behavior
{
    public $tags;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'eventFind',
            ActiveRecord::EVENT_AFTER_UPDATE => 'Save',
            ActiveRecord::EVENT_AFTER_INSERT => 'Insert',
            ActiveRecord::EVENT_BEFORE_DELETE => 'Delete',
        ];
    }

    private function clearPostTags()
    {
        if($this->owner->postTagAssigns)
        {
            foreach ($this->owner->postTagAssigns as $tag) 
            {
                $tag->delete();
            }
        }
    }

    private function insertPostTags()
    {
        if($this->tags)
        {
            foreach ($this->tags as $tag) 
            {
                $tg = PostTag::find()->where(['name' => $tag])->one();
                if(!$tg)
                {
                    // Убрана возможность создавать новые теги
                    return false; 
                    // $tg = new PostTag;
                    // $tg->name = $tag;
                    // $tg->save();
                }

                $tgs = new PostTagAssign;
                $tgs->post_id = $this->owner->id;
                $tgs->tag_id = $tg->id;
                $tgs->save();
            }
        }
    }

    public function eventFind($event)
    {
        $this->tags = array_keys(ArrayHelper::map($this->owner->postTags, 'name', 'id'));
    }

    public function Save($event)
    {
        $this->clearPostTags();

        $this->insertPostTags();

        return true;
    }

    public function Insert($event)
    {
        $this->insertPostTags();

        return true;
    }

    public function Delete($event)
    {
        $this->clearPostTags();

        return true;
    }
}
