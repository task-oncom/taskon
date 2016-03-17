<?php

namespace common\modules\blog\models;

use Yii;

use common\modules\blog\models\Post;
use common\modules\blog\models\PostTag;

/**
 * This is the model class for table "posts_tags_assign".
 *
 * @property integer $id
 * @property integer $tag_id
 * @property integer $post_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Posts $post
 * @property PostsTags $tag
 */
class PostTagAssign extends \common\components\ActiveRecordModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts_tags_assign';
    }

    public function name()
    {
        return 'Связь тегов и постов';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id', 'post_id'], 'required'],
            [['tag_id', 'post_id', 'created_at', 'updated_at'], 'integer'],
            [['tag_id', 'post_id'], 'unique', 'targetAttribute' => ['tag_id', 'post_id'], 'message' => 'The combination of Tag ID and Post ID has already been taken.'],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => PostTag::className(), 'targetAttribute' => ['tag_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tag_id' => 'Тег',
            'post_id' => 'Запись',
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
    public function getTag()
    {
        return $this->hasOne(PostTags::className(), ['id' => 'tag_id']);
    }
}
