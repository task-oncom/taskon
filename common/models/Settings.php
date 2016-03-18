<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property string $id
 * @property string $module_id
 * @property string $code
 * @property string $name
 * @property string $value
 * @property string $element
 * @property integer $hidden
 * @property string $description
 */
class Settings extends \common\components\ActiveRecordModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }
	
	public function name() {
		return 'Настройки';
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module_id', 'code', 'name', /*'value',*/ 'element', /*'description'*/], 'required'],
            [['value', 'element'], 'string'],
            [['hidden'], 'integer'],
            [['module_id', 'code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 2550],
            [['code'], 'unique'],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'module_id' => 'Модуль',
            'code' => 'Код',
            'name' => 'Название',
            'value' => 'Значение',
            'element' => 'Элемент',
            'hidden' => 'Скрытый',
            'description' => 'Описание',
        ];
    }

    public static function getValue($code)
    {
        $model = Settings::find()->where(['code' => $code])->one();

        if($model)
        {
            return $model->value;
        }

        return;
    }
}
