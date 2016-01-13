<?php
namespace common\components\activeRecordBehaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class DateFormatBehavior extends Behavior
{
    const DB_DATE_TIME_FORMAT = 'Y-m-d h:i:c';
    const DB_DATE_FORMAT      = 'Y-m-d';
    const SITE_DATE_FORMAT      = 'd.m.Y';
    const SITE_DATE_TIME_FORMAT = 'd.m.Y h:i:c';

    public $not_formattable_attrs = array('created_at', 'updated_at');

    public $fields = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'myAfterFind',
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'myBeforeValidate',
        ];
    }

    public function myAfterFind(){

        $model = $this->owner;

        foreach ($this->fields as $attr => $type) {

            if (in_array($attr, $this->not_formattable_attrs))
            {
                continue;
            }

            if (!$model->$attr)
            {
                continue;
            }

            /*if (Yii::$app->dater->isDbDate($model->$attr))
            {
                continue;
            }*/

            if(empty($model->$attr))
            {
                continue;
            }

            if ($type == 'date')
            {

                $model->$attr = date(self::SITE_DATE_FORMAT, strtotime($model->$attr));
            }
            else if (in_array($type, array('timestamp', 'datetime')))
            {
                $model->$attr = date(self::SITE_DATE_TIME_FORMAT, strtotime($model->$attr));
            }
        }
    }

    public function myBeforeValidate()
    {
        $model = $this->owner;

        foreach ($this->fields as $attr => $type)
        {
            if (in_array($attr, $this->not_formattable_attrs)) 
            {
            	continue;
            }

            if (!$model->$attr)
            {
                continue;
            }

            if(empty($model->$attr))
            {
                continue;
            }

            if (strtoupper($model->$attr) == 'NOW()') 
            {
            	continue;
            }
            
            /*if (Yii::$app->dater->isDbDate($model->$attr))
            {
            	continue;	
            }*/

            if ($type == 'date')
            {	
            	if(date('Y-m-d', strtotime($model->$attr)) != $model->$attr) {
                    //die('--'.$model->$attr);
                    $model->$attr = date(self::DB_DATE_FORMAT, strtotime($model->$attr));
                }
            }
            else if (in_array($type, array('timestamp', 'datetime')))
            {	
                $model->$attr = date(self::DB_DATE_TIME_FORMAT, strtotime($model->$attr));
            }
        }
    }


    /**
     * Добавляет гораничение по временному диапазону на заданный атрибут.
     * Виджет для временных диапазонов - FJuiDatePicker
     *
     * @param $criteria
     * @param $attribute_name
     */
    public function addTimeDiapasonCondition($criteria, $attribute_name)
    {
        $attr = $attribute_name;
        if (strpos('.', $attribute_name) !== false)
            list($prefix, $attr) = explode('.', $attribute_name);

        $start = '_' . $attr . '_start';
        $end   = '_' . $attr . '_end';

        if (isset($_GET[$start]) && ($start = strtotime($_GET[$start]))) {
            $criteria->addCondition($attribute_name . ">='" . $start . "'");
        }

        if (isset($_GET[$end]) && ($end = strtotime($_GET[$end]))) {
            $criteria->addCondition($attribute_name . "<='" . $end . "'");
            $criteria->addCondition($attribute_name . "<>''");
        }
    }
}
