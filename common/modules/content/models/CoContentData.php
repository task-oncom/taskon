<?php

namespace common\modules\content\models;

use Yii;

/**
 * This is the model class for table "co_content_data".
 *
 * @property integer $id
 * @property integer $content_id
 * @property string $title
 * @property string $short_description
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property CoContent $content
 */
class CoContentData extends \common\components\ActiveRecordModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'co_content_data';
    }
	
	public function name() {
		return 'Содержание контента';
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content_id', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 250],
            [['short_description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('content', 'ID'),
            'content_id' => Yii::t('content', 'Content ID'),
            'title' => Yii::t('content', 'Title'),
            'short_description' => Yii::t('content', 'Short Description'),
            'description' => Yii::t('content', 'Description'),
            'created_at' => Yii::t('content', 'Created At'),
            'updated_at' => Yii::t('content', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContent()
    {
        return $this->hasOne(CoContent::className(), ['id' => 'content_id']);
    }
}
