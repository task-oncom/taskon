<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 28.04.2015
 * Time: 1:37
 */

namespace common\components;
use common\modules\scoring\models\ScAnkets;

class DynamicModel extends \yii\base\DynamicModel{
    protected $_labels = [];

    public function attributeLabels()
    {
        return $this->_labels;
    }

    public function setAttributeLabels($labels = [])
    {
        $this->_labels = $labels;
    }

    public function saveTotal($data, $step = null, $id = null) {
        $ret = true;
        if(empty($id)) $id = \yii::$app->user->id;
        foreach($data['DynamicModel'] as $key=>$value){
            $ret = $ret && ScAnkets::setDynamicData($step, $id, $key, $value);
        }

        return $ret;
    }

    public  function loadTotal($data) {
        if(empty($data['DynamicModel'])) return false;
        return true;
    }

    public function beforeValidate() {

        if(isset($this->other_fund)) $this->other_fund = str_replace(' ','', $this->other_fund);
        if(isset($this->jobs_const_fund)) $this->jobs_const_fund = str_replace(' ','', $this->jobs_const_fund);

        if(isset($this->bank_last_credit_two_total)) $this->bank_last_credit_two_total = str_replace(' ','', $this->bank_last_credit_two_total);
        if(isset($this->bank_last_credit_total)) $this->bank_last_credit_total = str_replace(' ','', $this->bank_last_credit_total);

        return parent::beforeValidate();
    }

} 