<?php

namespace common\modules\content\models;

use Yii;

use common\modules\languages\models\Languages;
use common\modules\content\models\CoBlocksLang;

/**
 * This is the model class for table "co_blocks".
 *
 * @property string $id
 * @property string $lang
 * @property string $title
 * @property string $name
 * @property string $text
 * @property string $date_create
 * @property numeric $category_id
 */
class CoBlocks extends \common\components\ActiveRecordModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'co_blocks';
    }

    public function name() 
    {
        return 'Блоки';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'langs' => [
                'class' => 'common\modules\languages\components\LanguageHelperBehavior',
                'field' => 'block_id',
                'langClass' => 'common\modules\content\models\CoBlocksLang',
                'actions' => ['create', 'update', 'copy']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'name'], 'required'],
            [['title'], 'unique'],
            [['category_id'], 'safe'],
            [['title'], 'string', 'max' => 250],
            [['name'], 'string', 'max' => 50],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('content', 'ID'),
            'lang' => Yii::t('content', 'Язык'),
            'title' => Yii::t('content', 'Описание инфо-блока'),
            'name' => Yii::t('content', 'Перменная инфо-блока'),
            'text' => Yii::t('content', 'Текст'),
            'category_id' => 'Категория',
        ];
    }

    public function getLang($lang_id = null)
    {
        $lang_id = ($lang_id === null)? Languages::getCurrent()->id: $lang_id;

        return $this->hasOne(CoBlocksLang::className(), ['block_id' => 'id'])->where('lang_id = :lang_id', [':lang_id' => $lang_id]);
    }

    /**
     * @block string name block
     * @return HTML string
     */
    public static function printBlock($block) 
    {
        $model = self::findOne(['name' => $block]);
        return $model->lang->text;
    }

    public static function printStaticBlock($block, $params = [])
    {
        return Yii::$app->getView()->render( '@app/views/layouts/block/' . $block . '.php', $params);
    }
}
