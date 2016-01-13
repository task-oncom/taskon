<?php

namespace common\modules\faq\models;

use Yii;

/**
 * This is the model class for table "faq_category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 */
class FaqCategory extends \common\components\ActiveRecordModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'faq_category';
    }

    public function name() {
        return 'Вопрос-Ответ категории';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('faq', 'ID'),
            'name' => Yii::t('faq', 'Категория'),
            'created_at' => Yii::t('faq', 'Created At'),
            'updated_at' => Yii::t('faq', 'Updated At'),
        ];
    }
}
