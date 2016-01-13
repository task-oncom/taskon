<?php
namespace common\modules\rbac\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class AuthAssignment extends \common\components\ActiveRecordModel
{
	const PHOTOS_DIR = 'upload/news';


    public function name()
    {
        return 'Ассоциации групп пользователей';
    }


	/*public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}*/


	public static function tableName()
	{
		return 'auth_assignment';
	}


	public function rules()
	{
		return array(
			array('item_name, user_id', 'required'),
			array('item_name, user_id', 'string', 'max' => 64),
			//array('user_id', 'unique'),
			array('created_at, updated_at', 'safe'),

			//array('item_name, user_id', 'safe', 'on' => 'search'),
		);
	}


	/*public function relations()
	{
		return array(
			'role' => array(self::BELONGS_TO, 'AuthItem', 'item_name')
		);
	}*/
	
	public function getRole() {
		return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('item_name', $this->item_name, true);
		$criteria->compare('user_id', $this->user_id, true);
		$criteria->compare('bizrule', $this->bizrule, true);
		$criteria->compare('data', $this->data, true);

		return new ActiveDataProvider(get_class($this), array(
			'criteria' => $criteria
		));
	}


    public static function updateuser_idRole($user_id, $role)
    {
        $assignment = AuthAssignment::find(['user_id' => $user_id])->one();
        if (!$assignment)
        {
            $assignment = new AuthAssignment();
            $assignment->user_id = $user_id;
        }

        $assignment->item_name = $role;
        $assignment->save();
    }


    public function getuser_idsIds($roles)
    {
		if (!is_array($roles)) 
		{
			return;
		}
		
		foreach ($roles as $i => $role) 
		{
			$roles[$i] = "'" . $role .  "'";
		}
		
        $sql = "SELECT DISTINCT user_id
                       FROM " . $this->tableName() . "
                       WHERE item_name IN (" . implode(', ', $roles) . ")";
	
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        return ArrayHelper::extract($result, 'user_id');
    }
	
	public static function updateUserRole($user_id, $role)
    {
        /*$assignment = AuthAssignment::find(['user_id' => $user_id]);
        if (!$assignment)
        {
            $assignment = new AuthAssignment();
            $assignment->user_id = $user_id;
        }

        $assignment->item = $role;
        $assignment->save();*/
    }
}


















