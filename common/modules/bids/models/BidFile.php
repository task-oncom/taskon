<?php

namespace common\modules\bids\models;

use Yii;

/**
 * This is the model class for table "bids_files".
 *
 * @property integer $id
 * @property integer $bid_id
 * @property string $filename
 *
 * @property Bids $bid
 */
class BidFile extends \common\components\ActiveRecordModel
{
    const FILE_FOLDER = '/uploads/bids/';

    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bids_files';
    }

    /**
     * @inheritdoc
     */
    public function name() 
    {
        return 'Файл заявки';
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
            [['bid_id'], 'required'],
            [['bid_id'], 'integer'],
            [['filename'], 'string', 'max' => 100],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif, xls, xlsx, doc, docx, pdf', 'maxFiles' => 4],
            [['bid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bid::className(), 'targetAttribute' => ['bid_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file' => 'Прикрепленный файл',
            'bid_id' => 'Заявка',
            'filename' => 'Файл',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBid()
    {
        return $this->hasOne(Bid::className(), ['id' => 'bid_id']);
    }

    public function getUrl()
    {
        return Yii::$app->params['frontUrl'] . self::FILE_FOLDER . $this->filename;
    }

    public static function path()
    {
        return Yii::getAlias('@frontend/web') . self::FILE_FOLDER;
    }
}
