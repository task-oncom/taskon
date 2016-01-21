<?php

namespace common\modules\testings\controllers;

use Yii;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\modules\testings\models\TestingTest;
use common\modules\testings\models\SearchTestingTest;

class TestingTestAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            'View'   => 'Просмотр теста',
            'Create' => 'Создание теста',
            'Update' => 'Редактирование теста',
            'Delete' => 'Удаление теста',
            'Manage' => 'Управление тестами',
			'ImportTests' => 'Импорт вопросов и ответов к ним',
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
		$model = new TestingTest;

        Yii::$app->controller->page_title = 'Добавить тест';
        Yii::$app->controller->breadcrumbs = [
            ['Список тестов' => '/testings/testing-test-admin/manage'],
            'Добавить тест'
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['manage']);
        } else {
            $form = new \common\components\BaseForm('/common/modules/testings/forms/TestingTestForm', $model);
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
            ['Список тестов' => '/testings/testing-test-admin/manage'],
            'Редактировать тест'
        ];

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['manage']);
        } else {
            $form = new \common\components\BaseForm('/common/modules/testings/forms/TestingTestForm', $model);
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
		$searchModel = new SearchTestingTest();
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
		$model = $this->findModel($id);
		$form = new \common\components\BaseForm('/common/modules/testings/forms/TestingImportCSVForm', $model);
		$params = ['form' => $form, 'model' => $model];

		if($model->load(Yii::$app->request->post()))
		{
			$csv_file = CUploadedFile::getInstance($model, 'csv_file');

			$resource = CSVHelper::open($csv_file->tempName);

			try {
				$log = [];

				set_time_limit(60*3); // Максимальное время выполнения скрипта - 3 минуты

				$questionsCount = 0; // Кол-во загруженных вопросов
				$gammasCount = 0; // Кол-во гамм (не только созданных)

				while ($data = CSVHelper::fgetcsv($resource)) 
				{
					// если в файле пошли пустые строки, то выходим из цикла
					if (count($data) < 2) break;

					// извлечение переменных из CSV
					$gamma_type = trim($data[0]);
					// если первый столбец не указывает на цифры 1 или 2 (строительная и промышленная гаммы) - вся строка неправильная
					if (($gamma_type <> '1') && ($gamma_type <> '2')) continue;
					$gamma_name = trim(preg_replace('/\s+/', ' ',$data[1]));
					$q_name = trim(preg_replace('/\s+/', ' ',$data[2]));
					$q_type = trim(preg_replace('/\s+/', ' ',$data[3]));
					$a_name = trim(preg_replace('/\s+/', ' ',$data[4]));
					$a_isright = trim($data[5]);
					$author = trim($data[6]);

					// поиск или создание гаммы
					if ($gamma_name <> '') {

						$gammasCount++;

						$cr1 = new CDbCriteria;

						$cr1->addCondition('type = :main_gamma');
						$cr1->addCondition('name = :gamma');

						$cr1->params = array(
							':main_gamma' => $gamma_type,
							':gamma' => $gamma_name,
						);

						$gamma = TestingGamma::model()->find($cr1);

						if ($gamma === null) {
							$gamma = new TestingGamma;
							$gamma->name = $gamma_name;
							$gamma->type = $gamma_type;
							//$log[] = 'Гамма '.$gamma_name.' создана.';
						} else {
							//$log[] = 'Гамма '.$gamma_name.' уже существует.';
						}

						if ($gamma->save()) {
							//$log[] = 'Гамма "'.$gamma_name.'" сохранена.';
						} else {
							$log[] = 'Ошибка при сохранении гаммы "'.$gamma_name.'"';
						}
					}

					// создание вопроса
					if ($q_name <> '') {

						$question = new TestingQuestion;
						$question->text = $q_name;
						$question->type = $q_type;
						$question->test_id = $model->id;
						$question->gamma_id = $gamma->id;
						$question->author = $author;

						if ($question->save()) {
							//$log[] = 'Вопрос "'.$q_name.'" сохранен.';
							$questionsCount++;
						} else {
							$log[] = 'Ошибка при сохранении вопроса "'.$q_name.'"';
						}

					}

					// создание ответа
					$answer = new TestingAnswer;
					$answer->text = $a_name;
					if (($a_isright == 'да') || ($a_isright == 'Да') || ($a_isright == 'ДА') || ($a_isright == 'y') || ($a_isright == 'yes') || ($a_isright == 'Y')) {
						$answer->is_right = TestingAnswer::IS_RIGHT;
					} else {
						$answer->is_right = TestingAnswer::IS_NOT_RIGHT;
					}
					$answer->question_id = $question->id;

					if ($answer->save()) {
						//$log[] = 'Ответ "'.$a_name.'" сохранен.';
					} else {
						$log[] = 'Ошибка при сохранении ответа "'.$a_name.'"';
					}
				}

				// добавляем отчёт о кол-ве добавленных вопросов и гамм
				Yii::app()->user->setFlash('flash', '<i>Всего добавлено <b>' .$questionsCount. '</b> вопросов в <b>'.$gammasCount. '</b> гаммах. Перейти к '.CHtml::link('списку загруженных вопросов',array('/testings/testingQuestionAdmin/manage','test'=>$model->id)).'.</i>');

				$params = array(
					'model' => $model,
					'form' => $form,
					'log' => '<p>'.implode('</p><p>',$log).'</p>',
				);

			} catch (Exception $e){
				$params['log'] = 'Импорт прошел неудачно: ' . $e->getMessage();
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
        if (($model = TestingTest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
