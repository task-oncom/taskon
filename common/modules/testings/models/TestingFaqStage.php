<?php

class TestingFaqStage extends ActiveRecordModel
{
    const PAGE_SIZE = 10;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'testings_faq_stage';
	}

    public function name()
    {
        return 'Модель TestingFaqStage';
    }

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'faq_id' => 'Страница справки',
			'title' => 'Заголовок',
			'content' => 'Контент',
		);
	}

	public function rules()
	{
		return array(
			array('title, content, faq_id', 'required'),
			array('id, title, faq_id', 'safe', 'on' => 'search'),
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('id', $this->id);
		$criteria->compare('title', $this->title); 
		$criteria->compare('faq_id', Yii::app()->request->getQuery('faq'));

		return new ActiveDataProvider(get_class($this), array(
			'criteria' => $criteria
		));
	}
}