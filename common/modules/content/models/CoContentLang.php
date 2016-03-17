<?php

namespace common\modules\content\models;

use Yii;

use common\modules\content\models\CoContent;
use common\modules\languages\models\Languages;
use common\modules\content\models\CoBlocks;

/**
 * This is the model class for table "co_content_lang".
 *
 * @property integer $id
 * @property integer $content_id
 * @property integer $lang_id
 * @property string $name
 * @property string $title
 * @property string $text
 *
 * @property CoContent $content
 * @property Languages $lang
 */
class CoContentLang extends \common\components\ActiveRecordModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'co_content_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content_id', 'lang_id', 'name', 'title'], 'required'],
            [['content_id', 'lang_id'], 'integer'],
            [['text'], 'string'],
            [['name', 'title'], 'string', 'max' => 250],
            [['content_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoContent::className(), 'targetAttribute' => ['content_id' => 'id']],
            [['lang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Languages::className(), 'targetAttribute' => ['lang_id' => 'id']],
        ];
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
    public function name() 
    {
        return 'Языковой контент';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('content', 'ID'),
            'name' => Yii::t('content', 'Name'),
            'image' => 'Превью',
            'title' => Yii::t('content', 'Title'),
            'text' => Yii::t('content', 'Content'),
        ];
    }

    public function beforeSave($insert) 
    {
        $this->text = str_replace("../../../source/","/source/",$this->text);
        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(Languages::className(), ['id' => 'lang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContent()
    {
        return $this->hasOne(CoContent::className(), ['id' => 'content_id']);
    }

    /**
     * @return html
     */
    public function getFinishedContent()
    {
        $content = $this->text;
        $content = \common\components\AppManager::prepareWidget($content);
        
        $arrWhatReplace = [];
        $arrReplace = [];
        $arrWhatReplaceNext = [];
        $arrReplaceNext = [];

        $blocks = CoBlocks::find()->all();

        foreach($blocks as $block) 
        {
            if($block->lang)
            {
                $arrWhatReplace[] = '{' . $block->name . '}';
                $arrReplace[] = $block->lang->text;
                $arrWhatReplaceNext[] = '[' . $block->name . ']';
                $arrReplaceNext[] = $block->lang->text;
            }
        }

        $arrWhatReplaceNext[] = '[about-reviews]';
        $arrReplaceNext[] = CoBlocks::printStaticBlock('about-reviews');
        $arrWhatReplaceNext[] = '[reviews]';
        $arrReplaceNext[] = CoBlocks::printStaticBlock('reviews');
        $arrWhatReplaceNext[] = '[content-phone]';
        $arrReplaceNext[] = \common\models\Settings::getValue('content-phone');
        $arrWhatReplaceNext[] = '[cases]';
        $arrReplaceNext[] = CoBlocks::printStaticBlock('cases');
        $arrWhatReplaceNext[] = '[projects]';
        $arrReplaceNext[] = CoBlocks::printStaticBlock('projects');
        $arrWhatReplaceNext[] = '[case-subscribe]';
        $arrReplaceNext[] = CoBlocks::printStaticBlock('case-subscribe', ['title' => $this->title]);
        $arrWhatReplaceNext[] = '[case-more]';
        $arrReplaceNext[] = CoBlocks::printStaticBlock('case-more', ['model' => $this->content]);
        $arrWhatReplaceNext[] = '[footer]';
        $arrReplaceNext[] = \Yii::$app->getView()->render('@app/views/layouts/footer');
        $arrWhatReplaceNext[] = '[footer-index]';
        $arrReplaceNext[] = \Yii::$app->getView()->render('@app/views/layouts/footer-index');

        return str_replace($arrWhatReplaceNext, $arrReplaceNext,str_replace($arrWhatReplace, $arrReplace, $content));
    }
}
