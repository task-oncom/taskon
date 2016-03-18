<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "meta_tags".
 *
 * @property string $id
 * @property string $object_id
 * @property string $model_id
 * @property string $language
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 */
class MetaTags extends \common\components\ActiveRecordModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'meta_tags';
    }

    public function name() {
        return 'MetaTags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_id', 'model_id', 'lang_id'], 'required'],
            [['object_id', 'created_at', 'updated_at', 'lang_id'], 'integer'],
            [['model_id'], 'string', 'max' => 50],
            [['title', 'keywords', 'description'], 'string', 'max' => 300],
            [['object_id', 'model_id', 'lang_id'], 'unique', 'targetAttribute' => ['object_id', 'model_id', 'lang_id'], 'message' => 'The combination of ID объекта and Модель has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('content', 'ID'),
            'object_id' => Yii::t('content', 'ID объекта'),
            'model_id' => Yii::t('content', 'Модель'),
            'lang_id' => Yii::t('content', 'Язык'),
            'title' => 'Title',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'created_at' => Yii::t('content', 'Создано'),
            'updated_at' => Yii::t('content', 'Отредактирован'),
        ];
    }
}
