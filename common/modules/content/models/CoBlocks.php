<?php

namespace common\modules\content\models;

use Yii;

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

    public function name() {
        return 'Блоки';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'name', 'text'], 'required'],
            [['text'], 'string'],
            [['date_create', 'category_id'], 'safe'],
            [['lang'], 'string', 'max' => 2],
            [['module'], 'string', 'max' => 100],
            [['title'], 'string', 'max' => 250],
            [['name'], 'string', 'max' => 50],
            [['lang', 'title'], 'unique', 'targetAttribute' => ['lang', 'title'], 'message' => 'The combination of Язык and Заголовок has already been taken.'],
            [['lang', 'name'], 'unique', 'targetAttribute' => ['lang', 'name'], 'message' => 'The combination of Язык and Название (англ.) has already been taken.']
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
            'title' => Yii::t('content', 'Заголовок'),
            'name' => Yii::t('content', 'Наименование виджета'),
            'text' => Yii::t('content', 'Текст'),
            'date_create' => Yii::t('content', 'Добавлено'),
            'category_id' => 'Категория',
        ];
    }

    /**
     * @block string name block
     * @return HTML string
     */
    public static function printBlock($block) {
        $model = self::findOne(['name'=>$block]);
        return $model->text;
    }

    public static function printStaticBlock($block, $addPath = false)
    {
        return \yii::$app->getView()->render( '@app/views/layouts/block/' . $block . '.php');
        if ($addPath) {
            return \yii::$app->getView()->render( '@app/views/layouts/block/' . $block . '.php');
        }
        else
            return \yii::$app->getView()->render( '/block/'.$block);
    }

    public function afterFind() {
        parent::afterFind();
        if(Yii::$app->controller->id !='block-admin')
        $this->text = \common\components\AppManager::prepareWidget($this->text);
        $this->text = str_replace('../../../','/',$this->text);
    }

    public function beforeSave($insert) {
        $this->text = str_replace("../../../source/","http://taskon.soc-zaim.ru/source/",$this->text);
        return parent::beforeSave($insert);
    }

}
