<?php

namespace common\modules\main\models;

use Yii;

/**
 * This is the model class for table "counts".
 *
 * @property integer $id
 * @property string $name
 * @property string $count
 * @property integer $created_at
 * @property integer $updated_at
 */
class Counts extends \common\components\ActiveRecordModel
{
    public function name() {
        return 'Счетчики';
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'counts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['count'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Счетчик',
            'count' => 'Код счетчика',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
        ];
    }
}
