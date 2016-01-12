<?php
namespace common\modules\rbac\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\modules\rbac\models\AuthAssignment;

class AuthItemSearch extends AuthItem{
	
	public function search($params){
    $query = AuthItem::find();
	$query->andFilterWhere(['type' => parent::TYPE_OPERATION]);
	
    $dataProvider = new ActiveDataProvider([
        'query' => $query,
    ]);

    if (!($this->load($params) && $this->validate())) {
        return $dataProvider;
    }
	


    return $dataProvider;
}
	
}
