<?php

namespace common\modules\blog\models;

use Yii;

use common\modules\languages\models\Languages;
use common\modules\blog\models\Post;

/**
 * This is the model class for table "posts_lang".
 *
 * @property integer $id
 * @property integer $post_id
 * @property integer $lang_id
 * @property string $title
 * @property string $text
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Posts $post
 * @property Languages $lang
 */
class PostLang extends \common\components\ActiveRecordModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts_lang';
    }

    public function name()
    {
        return 'Языковые посты';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'lang_id'], 'required'],
            [['post_id', 'lang_id', 'created_at', 'updated_at'], 'integer'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
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
            'post_id' => 'Запись',
            'lang_id' => 'Язык',
            'title' => 'Заголовок',
            'text' => 'Контент',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(Languages::className(), ['id' => 'lang_id']);
    }

    public function cutText($chars)
    {
        $text = strip_tags($this->text, '<a>');

        if(strlen($text) > $chars)
        {
            $text = $text . " ";
            $text = substr($text, 0, $chars);
            $text = substr($text, 0, strrpos($text, ' '));
            $text = $text . '...';

            return '<p>'.$text.'</p><p><a href="'.$this->post->getFullUrl().'">Читать...</a></p>';
        }

        return $this->text;
    }
}
