<?php

namespace common\modules\testings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use common\modules\testings\models\Answer;
use common\modules\testings\models\Theme;
use common\modules\testings\models\Test;
use common\modules\testings\models\QuestionPassing;
use common\modules\testings\models\QuestionImage;

class Question extends \common\components\ActiveRecordModel
{
    const PAGE_SIZE = 10;

	const DELIMITER = ';';

	const ONE_OPTION = 1;
	const FEW_OPTIONS = 2;
	const USER_ANSWER = 3;

	const ACTIVE = 1;
	const HIDDEN = 0;

	const IMAGES_FOLDER = '/uploads/questions/';

	public $filesUpload;

    public static $type_list = [
        self::ONE_OPTION => 'Нужно выбрать один вариант',
        self::FEW_OPTIONS => 'Нужно выбрать несколько вариантов',
		self::USER_ANSWER => 'Нужно ввести свой вариант ответа',
    ];

    public static $active_list = [
        self::ACTIVE => 'Активен',
        self::HIDDEN => 'Скрыт',
    ];

	public static function tableName()
	{
		return 'testings_questions';
	}

    public function name()
    {
        return 'Вопросы';
    }

	public function attributeLabels() 
	{
		return [
			'is_active' => 'Активен/скрыт',
			'test_id' => 'Тест',
			'theme_id' => 'Тема',
			'text' => 'Текст вопроса',
			'type' => 'Тип вопроса',
			'create_date' => 'Время создания',
			'filesUpload' => 'Изображения'
		];
	}

	/**
     * @inheritdoc
     */
	public function rules()
	{
		return [
			[['theme_id', 'test_id', 'text', 'is_active', 'type'], 'required'],
			[['theme_id', 'test_id', 'type', 'is_active'], 'integer'],
			[['filesUpload'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 4],
        ];
	}

	/**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
	            'class' => TimestampBehavior::className(),
	            'createdAtAttribute' => 'create_date',
	            'updatedAtAttribute' => null,
	            'value' => new Expression('NOW()'),
	        ],
        ];
    }

    public function beforeDelete()
	{
	    if (parent::beforeDelete()) 
	    {
	       	$this->deleteFiles();
	        return true;
	    } 
	    else 
	    {
	        return false;
	    }
	}

    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['question_id' => 'id']);
    }

    public function getTheme()
    {
        return $this->hasOne(Theme::className(), ['id' => 'theme_id']);
    }

    public function getTest()
    {
        return $this->hasOne(Test::className(), ['id' => 'test_id']);
    }

    public function getPassings()
    {
        return $this->hasMany(QuestionPassing::className(), ['question_id' => 'id']);
    }

    public function getFiles()
    {
        return $this->hasMany(QuestionImage::className(), ['question_id' => 'id']);
    }

	public function getRightAnswer() 
	{
		$query = Answer::find()->where(['question_id' => $this->id]);

		switch ($this->type) 
		{
			case self::ONE_OPTION:
				$model = $query->andWhere(['is_right' => 1])->one();

				if($model)
				{
					$answer = trim(preg_replace('/\s+/', ' ', $model->text));
				} 
				else 
				{
					$answer = null;
				}
				break;

			case self::FEW_OPTIONS:
				$models = $query->andWhere(['is_right' => 1])->all();

				if ($models) 
				{
					$arr = array();

					foreach ($models as $model)
					{
						$arr[] = trim(preg_replace('/\s+/', ' ', $model->text));
					}

					sort($arr);
					$answer = implode(self::DELIMITER, $arr);
				} 
				else 
				{
					$answer = null;
				}
				break;

			case self::USER_ANSWER:
				$models = $query->one();
				if ($model) 
				{
					$answer = trim(preg_replace('/\s+/', ' ', $model->text));
				} 
				else 
				{
					$answer = null;
				}
				break;
		}
		return $answer;
	}
	
	public static function getThemesList() 
	{
		return \yii\helpers\ArrayHelper::map(Theme::find()->all(), 'id', 'name');
	}

	public function getPath() 
	{
		return Yii::getAlias('@frontend/web') . self::IMAGES_FOLDER;
	}

	public function upload()
    {
        if ($this->validate()) 
        {
        	if(!file_exists($this->getPath()))
            {
                mkdir($this->getPath(), 0777, true);
            }

            foreach ($this->filesUpload as $file) 
            {
            	$filename = date('dmYHis-') . uniqid() . '.' . $file->extension;
                $file->saveAs($this->getPath() . $filename);

                $image = new QuestionImage;
                $image->question_id = $this->id;
                $image->filename = $filename;
                $image->save();
            }

            return true;
        } 
        else 
        {
            return false;
        }
    }

    public function deleteFiles()
    {
    	if($this->files)
    	{
    		foreach ($this->files as $file) 
    		{
    			if(file_exists($this->getPath() . $file->filename))
		        {
		        	unlink($this->getPath() . $file->filename);
		        }
		        $file->delete();
    		}
    	}
    }
}
