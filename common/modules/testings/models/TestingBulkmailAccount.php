<?php

class TestingBulkmailAccount extends ActiveRecordModel
{
	const PAGE_SIZE = 10;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return 'bulkmail_accounts';
	}


    public function name()
    {
        return 'Модель TestingBulkmailAccount';
    }


	public function behaviors(){
        $behaviors = array();// parent::behaviors();
		$behaviors['CTimestampBehavior'] = array(
			'class' => 'zii.behaviors.CTimestampBehavior',
			'createAttribute' => 'create_date',
			'updateAttribute' => 'create_date',
		);
        return $behaviors;		
	}

	public function rules()
	{
		return array(
			array('server, port, email, username, password', 'required'),
			array('email', 'email'),
			array('email', 'unique'),
			array('port', 'numerical', 'integerOnly' => true),
			array('server, email, username, password, smtp_secure', 'length', 'max' => 200),

			array('id, server, port, email, username, password, create_date, smtp_secure', 'safe', 'on' => 'search'),
		);
	}


	public function relations()
	{
		return array(
		);
	}


	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('id', $this->id);
		$criteria->compare('server', $this->server, true);
		$criteria->compare('port', $this->port);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('username', $this->username, true);
		$criteria->compare('smtp_secure', $this->smtp_secure, true);
		$criteria->compare('password', $this->password, true);
		$criteria->compare('create_date', $this->create_date, true);

		return new ActiveDataProvider(get_class($this), array(
			'criteria' => $criteria
		));
	}
}