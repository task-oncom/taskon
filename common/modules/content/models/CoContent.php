<?php

namespace common\modules\content\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use frontend\modules\sitemap\behaviors\SitemapBehavior;
use \yii\helpers\Url;

use common\modules\content\models\CoBlocks;
use common\modules\content\models\CoContentLang;
use common\modules\languages\models\Languages;
use common\models\MetaTags;

/**
 * This is the model class for table "co_content".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $url
 * @property string $name
 * @property string $title
 * @property string $text
 * @property integer $active
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property CoCategory $category
 */
class CoContent extends \common\components\ActiveRecordModel
{
    const CUSTOM_DARK = 'dark';
    const CUSTOM_WHITE = 'white';

    const TYPE_SIMPLE = 0;
    const TYPE_LANDING = 1;

    public $copyLangs;

    public static $cutom_list = [
        self::CUSTOM_DARK => 'Темный',
        self::CUSTOM_WHITE => 'Светлый',
    ];

    public static $type_titles = [
        self::TYPE_SIMPLE => 'Простой',
        self::TYPE_LANDING => 'Лендинг',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'co_content';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'meta' => [
                'class' => 'common\components\activeRecordBehaviors\MetaTagBehavior',
                'actions' => ['create', 'update', 'copy']
            ],
            'langs' => [
                'class' => 'common\modules\languages\components\LanguageHelperBehavior',
                'field' => 'content_id',
                'langClass' => 'common\modules\content\models\CoContentLang',
                'actions' => ['create', 'update', 'copy']
            ],
            'file' => [
                'class' => 'common\components\activeRecordBehaviors\FileUploadBehavior',
                'path' => '@frontend/web',
                'folder' => '/uploads/content/',
                'field' => 'preview'
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => time(),
            ],
            'sitemap' => [
                'class' => SitemapBehavior::className(),
                'scope' => function ($model) {
                    /** @var \yii\db\ActiveQuery $model */
                    $model->select(['url', 'updated_at', 'priority']);
                    $model->andWhere(['active' => 1]);
                },
                'dataClosure' => function ($model) {
                    return [
                        'loc' => Url::to($model->url),
                        'lastmod' => date('c', $model->updated_at),
                        'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                        'priority' => $model->priority
                    ];
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function name() {
		return 'Контент';
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active', 'type'], 'integer'],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif'],
            [['url', 'type'], 'required'],
            [['url'], 'string', 'max' => 250],
            [['category_id', 'priority', 'custom'], 'safe'],
            [['priority'], 'double']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('content', 'ID'),
            'type' => 'Тип страницы',
            'category_id' => Yii::t('content', 'Category ID'),
            'url' => Yii::t('content', 'Url'),
            'name' => Yii::t('content', 'Name'),
            'file' => 'Превью',
            'custom' => 'Заголовок',
            'priority' => 'Приоритет в Sitemap',
            'title' => Yii::t('content', 'Title'),
            'text' => Yii::t('content', 'Content'),
            'active' => Yii::t('content', 'Active'),
            'created_at' => Yii::t('content', 'Created At'),
            'updated_at' => Yii::t('content', 'Updated At'),
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
    public function getCategory()
    {
        return $this->hasOne(CoCategory::className(), ['id' => 'category_id']);
    }

    public function getLang($lang_id = null)
    {
        $lang_id = ($lang_id === null)? Languages::getCurrent()->id: $lang_id;

        return $this->hasOne(CoContentLang::className(), ['content_id' => 'id'])->where('lang_id = :lang_id', [':lang_id' => $lang_id]);
    }

    public function getLangs()
    {
        return $this->hasMany(CoContentLang::className(), ['content_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->category_id==4) { // <<< С этим надо что то делать, очень много привязок динамичных данных
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, Yii::$app->urlManager->createAbsoluteUrl('/triggers/default/recheckcases'));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            if (!curl_exec($curl)) {
                echo curl_error($curl);
            }
        }
    }
}
