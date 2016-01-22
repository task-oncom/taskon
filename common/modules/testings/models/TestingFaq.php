<?php

class TestingFaq extends ActiveRecordModel
{
    const PAGE_SIZE = 10;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'testings_faq';
	}

    public function name()
    {
        return 'Модель TestingFaq';
    }

    public function relations()
	{
		return array(
			'stages' => array(self::HAS_MANY, 'TestingFaqStage', 'faq_id'),
		);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'title' => 'Заголовок',
			'content' => 'Контент',
			'url' => 'Ссылка',
		);
	}

	public function rules()
	{
		return array(
			array('title, url', 'required'),
			array('content', 'safe'),
			array('url', 'unique'),
			array('id, title, url', 'safe', 'on' => 'search'),
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('id', $this->id);
		$criteria->compare('title', $this->title);
		$criteria->compare('url', $this->url);

		return new ActiveDataProvider(get_class($this), array(
			'criteria' => $criteria
		));
	}
}