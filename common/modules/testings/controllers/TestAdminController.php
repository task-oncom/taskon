<?php

namespace common\modules\testings\controllers;

use Yii;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Html;

use common\modules\testings\models\Test;
use common\modules\testings\models\SearchTest;
use common\modules\testings\models\Question;
use common\modules\testings\models\Theme;
use common\modules\testings\models\Answer;

class TestAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            'View'   => 'Просмотр теста',
            'Create' => 'Создание теста',
            'Update' => 'Редактирование теста',
            'Delete' => 'Удаление теста',
            'Manage' => 'Управление тестами',
			'Import-tests' => 'Импорт вопросов и ответов к ним',
        );
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

	public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

	public function actionCreate()
	{
		$model = new Test;

        Yii::$app->controller->page_title = 'Добавить тест';
        Yii::$app->controller->breadcrumbs = [
            ['Список тестов' => '/testings/test-admin/manage'],
            'Добавить тест'
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['manage']);
        } else {
            $form = new \common\components\BaseForm('/common/modules/testings/forms/TestForm', $model);
            return $this->render('create', [
                'model' => $model,
                'form' => $form->out
            ]);
        }
	}


	public function actionUpdate($id)
	{
		Yii::$app->controller->page_title = 'Редактировать тест';
        Yii::$app->controller->breadcrumbs = [
            ['Список тестов' => '/testings/test-admin/manage'],
            'Редактировать тест'
        ];

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['manage']);
        } else {
            $form = new \common\components\BaseForm('/common/modules/testings/forms/TestForm', $model);
            return $this->render('update', [
                'model' => $model,
                'form' => $form->out
            ]);
        }
	}


	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

        return $this->redirect(['manage']);
	}


	public function actionManage()
	{
		$searchModel = new SearchTest();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->controller->page_title = 'Список тестов';
        Yii::$app->controller->breadcrumbs = [
            'Список тестов',
        ];

        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}

	public function actionImportTests($id)
	{
		$model = new Test;

		$model->scenario = 'upload';

		Yii::$app->controller->page_title = 'Импорт вопросов и ответов к ним';
        Yii::$app->controller->breadcrumbs = [
            'Импорт вопросов и ответов к ним',
        ];
		
		$params = ['model' => $model];

		if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()))
		{
			$model->csv_file = UploadedFile::getInstance($model, 'csv_file');

            if($model->upload()) 
            {
				try 
				{
					$log = [];

					set_time_limit(60*3); // Максимальное время выполнения скрипта - 3 минуты

					$questionsCount = 0; // Кол-во загруженных вопросов
					$themesCount = 0; // Кол-во тем (не только созданных)

					$inputFileType = \PHPExcel_IOFactory::identify($model->file);
				    $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
				    $objPHPExcel = $objReader->load($model->file);

				    $sheet = $objPHPExcel->getSheet(0);

					for ($i = 3; $i <= $sheet->getHighestRow(); $i++)
					{
						// извлечение переменных из XLS
						$theme_name = trim(preg_replace('/\s+/', ' ', $sheet->getCell('A' . $i)->getValue()));
						$question_name = trim(preg_replace('/\s+/', ' ', $sheet->getCell('B' . $i)->getValue()));
						$question_type = trim(preg_replace('/\s+/', ' ', $sheet->getCell('C' . $i)->getValue()));
						$answer_name = trim(preg_replace('/\s+/', ' ', $sheet->getCell('D' . $i)->getValue()));
						$answer_isright = trim($sheet->getCell('E' . $i)->getValue());

						// поиск или создание темы
						if ($theme_name <> '') 
						{
							$themesCount++;

							$theme = Theme::find()->where(['name' => $theme_name])->one();

							if(!$theme) 
							{
								$theme = new Theme;
								$theme->name = $theme_name;
							} 
							else 
							{
								$log[] = 'Тема ' . $theme_name . ' уже существует.';
							}

							if(!$theme->save()) 
							{
								$log[] = 'Ошибка при сохранении темы "' . $theme_name . '"';
							}
						}

						// создание вопроса
						if ($question_name <> '') 
						{
							$question = new Question;
							$question->text = $question_name;
							$question->type = $question_type;
							$question->test_id = $id;
							$question->theme_id = $theme->id;
							$question->is_active = Question::ACTIVE;

							if($question->save()) 
							{
								$questionsCount++;
							} 
							else 
							{
								$log[] = 'Ошибка при сохранении вопроса "' . $question_name . '"';
							}
						}

						// создание ответа
						$answer = new Answer;
						$answer->text = $answer_name;

						if (in_array($answer_isright, Answer::$xls_rights_list)) 
						{
							$answer->is_right = Answer::IS_RIGHT;
						} 
						else 
						{
							$answer->is_right = Answer::IS_NOT_RIGHT;
						}

						$answer->question_id = $question->id;

						if (!$answer->save()) 
						{
							$log[] = 'Ошибка при сохранении ответа "' . $answer_name . '"';
						}
					}

					// добавляем отчёт о кол-ве добавленных вопросов и гамм
					Yii::$app->session->setFlash('flash', '<i>Всего добавлено <b>' . $questionsCount . '</b> вопросов в <b>' . $themesCount . '</b> темах. Перейти к ' . Html::a('списку загруженных вопросов', ['/testings/question-admin/manage', 'test'=>$model->id]) . '.</i>');

					$params['log'] = '<p>' . implode('</p><p>', $log) . '</p>';
				}
				catch (Exception $e)
				{
					$params['log'] = 'Импорт прошел неудачно: ' . $e->getMessage();
				}

				$model->deleteFile();
			}
			else
			{
				Yii::$app->session->setFlash('flash', 'Произошла ошибка при загрузке файла. Обратитесь к администратору!');
			}
		}

		return $this->render('import-tests', $params);
	}

	/**
     * Finds the Faq model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Faq the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Test::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
