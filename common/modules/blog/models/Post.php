<?php

namespace common\modules\blog\models;

use Yii;
use yii\helpers\Url;

use common\modules\languages\models\Languages;
use common\modules\blog\models\PostLang;
use common\modules\blog\models\PostTag;
use common\modules\users\models\User;
use common\models\MetaTags;
use common\modules\sessions\models\SessionUrl;

/**
 * This is the model class for table "posts".
 *
 * @property integer $id
 * @property string $url
 * @property integer $active
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property PostsLang[] $postsLangs
 * @property PostsTagsAssign[] $postsTagsAssigns
 * @property PostsTags[] $tags
 */
class Post extends \common\components\ActiveRecordModel
{
    const ACTIVE_FALSE = 0;
    const ACTIVE_TRUE = 1;

    const PAGE_SIZE = 2;

    public static $active_title = [
        self::ACTIVE_FALSE => 'Скрыта',
        self::ACTIVE_TRUE => 'Доступна',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts';
    }

    public function name()
    {
        return 'Посты';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'active'], 'required'],
            [['active', 'created_at', 'updated_at', 'author_id'], 'integer'],
            [['url'], 'string', 'max' => 255],
            [['url'], 'unique'],
            [['preview', 'unlinkFile', 'tags'], 'safe'],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'meta' => [
                'class' => 'common\components\activeRecordBehaviors\MetaTagBehavior',
                'actions' => ['create', 'update']
            ],
            'langs' => [
                'class' => 'common\modules\languages\components\LanguageHelperBehavior',
                'field' => 'post_id',
                'langClass' => 'common\modules\blog\models\PostLang',
                'actions' => ['create', 'update']
            ],
            'tags' => [
                'class' => 'common\modules\blog\components\TagBehavior',
            ],
            'Timestamp'      => [
                'class' => '\yii\behaviors\TimestampBehavior',
            ],
            'file' => [
                'class' => 'common\components\activeRecordBehaviors\FileUploadBehavior',
                'thumb' => true,
                'thumbSize' => ['280', '141'],
                'path' => '@frontend/web',
                'folder' => '/uploads/blog/',
                'field' => 'preview'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Автор',
            'url' => 'Ссылка',
            'active' => 'Видимость',
            'file' => 'Изображение',
            'tags' => 'Теги',
            'preview' => 'Изображение',
            'unlinkFile' => 'Удалить изображение',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetaTags($lang_id = null) 
    {
        $query = $this->hasMany(MetaTags::className(), ['object_id' => 'id'])->where(['model_id'  => get_class($this)]);

        if($lang_id)
        {
            $query->andWhere(['lang_id' => $lang_id]);
        }

        return $query;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetaTag($lang_id = null) 
    {
        $lang_id = ($lang_id === null)? Languages::getCurrent()->id: $lang_id;

        return $this->hasOne(MetaTags::className(), ['object_id' => 'id'])->where([
            'model_id'  => get_class($this),
            'lang_id' => $lang_id
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang($lang_id = null)
    {
        $lang_id = ($lang_id === null)? Languages::getCurrent()->id: $lang_id;

        return $this->hasOne(PostLang::className(), ['post_id' => 'id'])->where('lang_id = :lang_id', [':lang_id' => $lang_id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLangs()
    {
        return $this->hasMany(PostLang::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostTags()
    {
        return $this->hasMany(PostTag::className(), ['id' => 'tag_id'])->viaTable('posts_tags_assign', ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostTagAssigns()
    {
        return $this->hasMany(PostTagAssign::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getViews()
    {
        return SessionUrl::find()->where(['url' => $this->getFullUrl()]);
    }

    public function getFullUrl()
    {
        return Url::to(['/blog/' . $this->url]);
    }

    public function getThumbnailUrl()
    {
        $path = pathinfo($this->preview);

        return $path['dirname'] . '/' . $path['filename'] . '.thumb.' . $path['extension'];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) 
        {
            $this->author_id = Yii::$app->user->identity->id;
            return true;
        } 
        else 
        {
            return false;
        }
    }
}
