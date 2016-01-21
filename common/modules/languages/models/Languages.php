<?php

namespace common\modules\languages\models;

use Yii;

/**
 * This is the model class for table "languages".
 *
 * @property integer $id
 * @property string $code
 * @property string $codeFull
 * @property string $name
 */
class Languages extends \common\components\ActiveRecordModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'languages';
    }
	
	public function name()
	{
		return "Языки";
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'codeFull', 'name'], 'required'],
            [['code'], 'string', 'max' => 2],
            [['codeFull'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 15],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('languages', 'ID'),
            'code' => Yii::t('languages', 'Language Code'),
            'codeFull' => Yii::t('languages', 'Locale Code'),
            'name' => Yii::t('languages', 'Name'),
        ];
    }
}
