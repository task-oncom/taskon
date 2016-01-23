<?php

namespace common\modules\testings\controllers;

use Yii;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use common\modules\testings\models\TestingQuestion;
use common\modules\testings\models\SearchTestingQuestion;

class TestingQuestionAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            'View'   => 'Просмотр вопроса',
            'Create' => 'Создание вопроса',
            'Update' => 'Редактирование вопроса',
            'Delete' => 'Удаление вопроса',
            'Manage' => 'Управление вопросами',
            'UpdateAnswer' => 'Изменение ответов'
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
		$model = new TestingQuestion;

        Yii::$app->controller->page_title = 'Добавить вопрос';
        Yii::$app->controller->breadcrumbs = [
            ['Список вопросов' => '/testings/testing-question-admin/manage'],
            'Добавить вопрос'
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['manage']);
        } else {
            $form = new \common\components\BaseForm('/common/modules/testings/forms/TestingQuestionForm', $model);
            return $this->render('create', [
                'model' => $model,
                'form' => $form->out
            ]);
        }
	}

	public function actionUpdate($id)
	{
		Yii::$app->controller->page_title = 'Редактировать вопрос';
        Yii::$app->controller->breadcrumbs = [
            ['Список вопросов' => '/testings/testing-question-admin/manage'],
            'Редактировать вопрос'
        ];

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['manage']);
        } else {
            $form = new \common\components\BaseForm('/common/modules/testings/forms/TestingQuestionForm', $model);
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
        $questions = [];

        $param = 'карт, изобр, изображение, скриншот, картинка, схема, рисунок, рис';
        // $param = trim(Setting::getValue('testings_questions_with_picture'));

        $words = empty($param) ? array() : explode(',', trim($param));

        if(Yii::$app->request->get('test') && !empty($words)) 
        {
            $query = TestingQuestion::find();

            for ($i = 0; $i < count($words); $i++) 
            {
                $query->orWhere(['like', 'text', trim($words[$i])]);
            }

            $query->andWhere(['test_id' => Yii::$app->request->get('test')]);

            $questions = ArrayHelper::map($query->all(), 'id', 'text');
        }

		$searchModel = new SearchTestingQuestion();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->controller->page_title = 'Список вопросов';
        Yii::$app->controller->breadcrumbs = [
            'Список вопросов',
        ];

        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'questions' => $questions,
            'questions_param' => $param,
        ]);
	}

	public function actionUpdateAnswer($id)
    {
		if (!Yii::app()->request->isAjaxRequest) {
			throw new CException('Прямой доступ запрещен');
		}

		if (!isset($_POST['t']) && !isset($_POST['c']) && !isset($_POST['us']))
        {
			throw new CException('Не переданы необходимые параметры');
        }

        $user = unserialize($_POST['us']);
        if ($user->getRole() != 'admin') {
			throw new CException('Ошибка доступа');
		}

		if ((isset($_POST['q'])) && ($_POST['q'] == 'new')) {
			$model = new TestingAnswer;
			$model->question_id = $id;
			
			echo $id;
		}
		else {
			$model = TestingAnswer::model()->findByPk((int) $id);

			if($model === null)
			{
				throw new CException('Запрошенный вопрос не существует');
			}
		}

		$model->text = $_POST['t'];
		$model->is_right = $_POST['c'];

		if ($model->save())
		{
			echo $model->id;
		}
		else {
			echo $model->id;
		}
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
        if (($model = TestingQuestion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
