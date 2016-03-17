<?php

namespace common\modules\content\models;

use Yii;

use common\modules\languages\models\Languages;

/**
 * This is the model class for table "co_blocks_lang".
 *
 * @property integer $id
 * @property integer $block_id
 * @property integer $lang_id
 * @property string $name
 * @property string $title
 * @property string $text
 *
 * @property CoBlocks $block
 * @property Languages $lang
 */
class CoBlocksLang extends \common\components\ActiveRecordModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'co_blocks_lang';
    }

    public function name() 
    {
        return 'Языковые блоки';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['block_id', 'lang_id',], 'required'],
            [['block_id', 'lang_id'], 'integer'],
            [['text'], 'string'],
            [['block_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoBlocks::className(), 'targetAttribute' => ['block_id' => 'id']],
            [['lang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Languages::className(), 'targetAttribute' => ['lang_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'block_id' => 'Block ID',
            'lang_id' => 'Lang ID',
            'text' => 'Текст',
        ];
    }

    public function afterFind() 
    {
        parent::afterFind();

        if(Yii::$app->controller->id !='block-admin')
        {
            $this->text = \common\components\AppManager::prepareWidget($this->text);
        }

        $this->text = str_replace('../../../', '/', $this->text);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlock()
    {
        return $this->hasOne(CoBlocks::className(), ['id' => 'block_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(Languages::className(), ['id' => 'lang_id']);
    }
}
