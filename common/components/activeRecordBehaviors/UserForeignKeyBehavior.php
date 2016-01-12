<?php
namespace common\components\activeRecordBehaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class UserForeignKeyBehavior extends Behavior
{
    public function beforeValidate($event)
    {
        $model = $this->getOwner();
        
    	if (array_key_exists('user_id', $model->attributes) && $model->user_id === null)
    	{
    		$model->user_id = Yii::app()->user->id;
    	}
    }
}
