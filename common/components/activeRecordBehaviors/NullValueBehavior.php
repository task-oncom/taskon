<?php
namespace common\components\activeRecordBehaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class NullValueBehavior extends Behavior
{
    public function beforeSave($event)
    {
        $model = $this->getOwner();

        $columns = Yii::$app->db->getSchema()->getTable($model->tableName())->columns;

        foreach ($model->attributes as $name => $value)
        {
            if (!$value && $columns[$name]->allowNull)
            {
                $model->$name = null;
            }
        }
    }
}
