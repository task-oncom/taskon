<?php

namespace common\modules\content\models;

use Yii;

/**
 * This is the model class for table "co_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 *
 * @property CoContent[] $coContents
 */
class CoCategory extends \common\components\ActiveRecordModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'co_category';
    }
	
	public function name() {
		return 'Категории контента';
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'url'], 'required'],
            [['name', 'url'], 'string', 'max' => 255],
//			[['url'], 'url'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('content', 'ID'),
            'name' => Yii::t('content', 'Name'),
            'url' => Yii::t('content', 'UrlCat'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoContents()
    {
        return $this->hasMany(CoContent::className(), ['category_id' => 'id']);
    }
}
