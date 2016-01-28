<?php

namespace common\modules\testings\models;

use Yii;

use common\modules\testings\models\Question;

/**
 * This is the model class for table "testings_questions_image".
 *
 * @property integer $id
 * @property integer $question_id
 * @property string $filename
 */
class QuestionImage extends \common\components\ActiveRecordModel
{
    const PAGE_SIZE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'testings_questions_image';
    }

    public function name()
    {
        return 'Изображения для вопросов';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'filename'], 'required'],
            [['question_id'], 'integer'],
            [['filename'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question_id' => 'Вопрос',
            'filename' => 'Изображение',
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

    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }

    public function getUrl()
    {
        return Yii::$app->params['frontUrl'] . Question::IMAGES_FOLDER . $this->filename;
    }
}
