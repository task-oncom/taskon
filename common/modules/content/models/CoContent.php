<?php

namespace common\modules\content\models;

use Yii;
use common\modules\content\models\CoBlocks;
use common\models\MetaTags;
use yii\base\Controller;
/**
 * This is the model class for table "co_content".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $url
 * @property string $name
 * @property string $title
 * @property string $text
 * @property integer $active
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property CoCategory $category
 */
class CoContent extends \common\components\ActiveRecordModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'co_content';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'meta' => [
                'class' => 'common\components\activeRecordBehaviors\MetaTagBehavior',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function name() {
		return 'Контент';
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active', 'created_at', 'updated_at'], 'integer'],
            [['url', 'name', 'text'], 'required'],
            [['url', 'name', 'title'], 'string', 'max' => 250],
            [['category_id', 'text'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('content', 'ID'),
            'category_id' => Yii::t('content', 'Category ID'),
            'url' => Yii::t('content', 'Url'),
            'name' => Yii::t('content', 'Name'),
            'title' => Yii::t('content', 'Title'),
            'text' => Yii::t('content', 'Content'),
            'active' => Yii::t('content', 'Active'),
            'created_at' => Yii::t('content', 'Created At'),
            'updated_at' => Yii::t('content', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetaTags() {
        return $this->hasOne(MetaTags::className(), [
             'object_id' => 'id',
            /*            */
        ])->where([
            'model_id'  => get_class($this),
            'language' => 'ru',]);

    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(CoCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return html
     */
    public function getContent() {
        $content = $this->text;

        return $content;
    }

    public function beforeSave($insert) {
        $this->text = str_replace("../../../source/","http://taskon.soc-zaim.ru/source/",$this->text);
        return parent::beforeSave($insert);
    }
}
