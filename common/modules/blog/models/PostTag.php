<?php

namespace common\modules\blog\models;

use Yii;

use yii\helpers\Url;

use common\modules\blog\models\Post;
use common\modules\blog\models\PostTagAssign;

/**
 * This is the model class for table "posts_tags".
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property PostsTagsAssign[] $postsTagsAssigns
 * @property Posts[] $posts
 */
class PostTag extends \common\components\ActiveRecordModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts_tags';
    }

    public function name()
    {
        return 'Теги';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя тега',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['id' => 'post_id'])
            ->viaTable('posts_tags_assign', ['tag_id' => 'id'])
            ->where(['active' => 1])
            ->orderBy(Post::tableName().'.created_at DESC')
            ->limit(Post::PAGE_SIZE);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAllPosts()
    {
        return $this->hasMany(Post::className(), ['id' => 'post_id'])
            ->viaTable('posts_tags_assign', ['tag_id' => 'id'])
            ->where(['active' => 1])
            ->orderBy(Post::tableName().'.created_at DESC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssigns()
    {
        return $this->hasMany(PostTagAssign::className(), ['tag_id' => 'id']);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) 
        {
            if($this->assigns)
            {
                foreach ($this->assigns as $assign) 
                {
                    $assign->delete();
                }
            }

            return true;
        } 
        else 
        {
            return false;
        }
    }

    public function getUrl()
    {
        return Url::to(['/blog/tag/' . $this->name]);
    }
}
