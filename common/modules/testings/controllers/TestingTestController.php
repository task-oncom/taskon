<?php

class TestingTestController extends BaseController
{
	const AUTH_COOKIE = 'test_user_cookie';
	const PASS_COOKIE = 'test_pass_cookie';
	const BROWSER_COOKIE = 'test_browser_cookie';

	public static function actionsTitles()
	{
		return array(
			'Index' => 'Тестирование',
			'Info' => 'Начало теста',
			'Pass' => 'Тестирование',
			'FinishTest' => 'Завершение теста',
			'Login' => 'Авторизация',
			'Logout' => 'Деавторизация',
			'SetAnswer' => 'Запись ответа на вопрос',
			'GenPass' => 'Генерация тестирования',
			'Statistic' => 'Статистика тестирования',
            'SendNotAttempt' => 'Отправка письма при использовании попыток',
		);
	}

	public function filters() {
		return CMap::mergeArray(parent::filters(), array(
			'checkAuth - login',
		));
	}

	private function encodePassword($pass) {
		return md5($pass.'the.longest.salt.ever.dont.even.try.to.decode.lol');
	}

	public $layout = '/layouts/testing';

	public $page_subtitle;

	public $logined_user;

	public function filterCheckAuth($filterChain) {
		// проверим для начала наличие куков
		if (!isset(Yii::app()->request->cookies[self::AUTH_COOKIE])
				|| !isset(Yii::app()->request->cookies[self::PASS_COOKIE]))
		{
			$this->redirect('/testings/testingTest/login');
		}

		// во вторую очередь проверим, есть ли такой юзер с таким айди
		$id = (int) Yii::app()->request->cookies[self::AUTH_COOKIE]->value;
		$pass = Yii::app()->request->cookies[self::PASS_COOKIE]->value;
		$user = TestingUser::model()->findByPk($id);

		if (!$user) {
			$this->redirect('/testings/testingTest/login');
		}

		// в конце проверим, совпадает ли пароль
		if ($this->encodePassword($user->password) <> $pass) {
			$this->redirect('/testings/testingTest/login');
		}

		$this->logined_user = $user;
		$filterChain->run();
	}

	public function actionSendNotAttempt($id)
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			$model = TestingPassing::model()->findByPK($id);

			if($model && $model->sendNotAttempt(Yii::app()->request->getPost('message')))
			{
				echo CJavaScript::jsonEncode(array(
					'success' => true,
					'message' => 'Сообщение отправлено!'
				));
			}
			
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		
	}

	public function actionLogin() {

		unset(Yii::app()->request->cookies[self::BROWSER_COOKIE]);
		if (isset($_POST['username']) && isset($_POST['password'])) {
			$user = TestingUser::model()->find('login = :login',array('login'=>$_POST['username']));
			// авторизация
			if ($user) {
				if ($user->password == $_POST['password']) {
					$cookie = new CHttpCookie(self::AUTH_COOKIE,$user->id);
					Yii::app()->request->cookies[self::AUTH_COOKIE] = $cookie;
					$cookie2 = new CHttpCookie(self::PASS_COOKIE, $this->encodePassword($user->password));
					Yii::app()->request->cookies[self::PASS_COOKIE] = $cookie2;
					$this->redirect('/testings/testingTest/index');
				}
			}
			Yii::app()->user->setFlash('flash','Введён неверный логин или пароль!');
		}
		$this->layout = '/layouts/auth';
		$this->render('auth_page');
	}

	public function actionLogout() {
		unset(Yii::app()->request->cookies[self::AUTH_COOKIE]);
		unset(Yii::app()->request->cookies[self::PASS_COOKIE]);
		unset(Yii::app()->request->cookies[self::BROWSER_COOKIE]);
		$this->redirect('index');
	}

	public function actionInfo($id)
	{
		$passing = TestingPassing::model()->findByPk($id);

		if($passing 
			&& $passing->user_id == Yii::app()->request->cookies[self::AUTH_COOKIE]->value
			&& $passing->status == TestingPassing::NOT_STARTED)
		{
			$this->render('info', array(
				'model' => $passing
			));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function actionIndex()
	{
		$user_id = Yii::app()->request->cookies[self::AUTH_COOKIE]->value;

        //отмечаем что пользователь авторизовывался
        $user = TestingUser::model()->findByPk($user_id);
        $user->is_auth = 1;
        $user->save();

		$cr3 = new CDbCriteria;
		$cr3->with = 'test.session';
		$cr3->addCondition('user_id = :user_id');
		$cr3->addCondition('test.id != 39');
		$cr3->addCondition('STR_TO_DATE(`session`.`start_date`,"%d.%m.%Y %H:%i") <= NOW()');
		$cr3->addCondition('(STR_TO_DATE(`session`.`end_date`,"%d.%m.%Y %H:%i") > NOW()) OR (STR_TO_DATE(`t`.`end_date`,"%d.%m.%Y %H:%i") > NOW())');
		$cr3->params = array(
			'user_id' => $user_id,
		);
		$cr3->group = 't.id';
		$cr3->together = true;

		$this->render('index', array(
			'present' => new ActiveDataProvider(
				'TestingPassing', 
				array(
					'criteria' => $cr3,
					'pagination' => array(
						'pageSize' => TestingPassing::PAGE_SIZE
					)
				)
			),
		));
	}

	public function actionSetAnswer($id)
	{
		header('Content-type: application/json');

		if(Yii::app()->request->isAjaxRequest)
		{
			$passing = TestingPassing::model()->findByPk($id);
		
			$cr = new CDbCriteria;
			$cr->addCondition('question_id = :question_id');
			$cr->addCondition('passing_id = :passing_id');
			$cr->params = array(
				':question_id' => $_POST['question_id'],
				':passing_id' => $passing->id,
			);

			$pq = TestingQuestionPassing::model()->find($cr);
			$pq->user_answer = $_POST['userAnswer'];
			$pq->answer_time = ceil($_POST['time']);

            if($pq->save())
            {
				$passing->recountPassResult();
				$passing->percent_rights = $passing->getPercent();

				if($passing->is_passed == TestingPassing::PASSED)
				{
					$passing->pass_date = date('d.m.Y H:i:s');
				}

				$passing->save();

				$data = array(
					'success' => true,
					'percent' => $pq->passing->percent,
				);

				$pstep = $pq->passing->messageStep;

				if(is_array($pstep))
				{
					$data['messageStep'] = $pstep['message'];
					$data['percentStep'] = $pstep['percent'];
				}
				else
				{
					$data['messageStep'] = null;
					$data['percentStep'] = null;
				}

            	echo CJavaScript::jsonEncode($data);
				Yii::app()->end();
            }
            else
            {
            	echo CJavaScript::jsonEncode(array(
					'massage' => 'Временные неполадки на сервере, попробуйте еще раз.'
				));
				Yii::app()->end();
            }		
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function actionFinishTest($id)
	{
		// we only allow deletion via POST request
		if(Yii::app()->request->isPostRequest)
		{
			Yii::log("POST FinisTest $id", 'error', 'testings');
            
            $log['get'] = CVarDumper::dumpAsString($_GET);
            $log['post'] = CVarDumper::dumpAsString($_POST);
            $log['server'] = CVarDumper::dumpAsString($_SERVER);
            $log['errors'] = array();
        
			$passing = TestingPassing::model()->findByPk($id);

			$this->layout = false;
			header('Content-type: application/json');

			// если тест сдан, результаты модифицировать нельзя
			if ($passing->pass_date === null) 
			{
				$passing->pass_date = date('d.m.Y H:i:s');

				$passing->recountPassResult();

				$passing->percent_rights = $passing->getPercent();
                if(!$passing->save()) {
                    $log['errors'][] = array(
                        'attributes' => $passing->attributes,
                        'errors' => $passing->errors,
                    );
                } else {
                    $log['passing-save'][] = array(
                        'attributes' => $passing->attributes,
                        'errors' => $passing->errors,
                    );
				}

				echo json_encode(array('status'=>'ok'));
				Yii::app()->end();
			} else {
                Yii::log('Тест уже был сдан!', 'error', 'testings');
				echo json_encode(array('massage'=>'Тест уже был сдан!'));
				Yii::app()->end();
			}
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function actionGenPass($id)
	{

		$passing = TestingPassing::model()->findByPk($id);

		$mg_gammas = array();
		$te_gammas = array();

		$user_id = Yii::app()->request->cookies[self::AUTH_COOKIE]->value;
		$cr3 = new CDbCriteria;
		$cr3->with = 'test.session';
		$cr3->addCondition('user_id = :user_id');
		$cr3->addCondition('t.id = :p_id');
		//$cr3->addCondition('t.is_passed is NULL');
		$cr3->addCondition('STR_TO_DATE(`session`.`start_date`,"%d.%m.%Y %H:%i") <= NOW()');
		$cr3->addCondition('(STR_TO_DATE(`session`.`end_date`,"%d.%m.%Y %H:%i") > NOW()) OR (STR_TO_DATE(`t`.`end_date`,"%d.%m.%Y %H:%i") > NOW())');
		$cr3->params = array(
			'user_id' => $user_id,
			'p_id' => $id
		);
		$cr3->group = 't.id';
		$cr3->together = true;

		$tp = TestingPassing::model()->find($cr3);

		if (empty($tp)) 
		{
			echo 'Доступ к этому тесту запрещен';
		}
		else 
		{
			// только проверив состояния, можно допустить юзера к тесту
			switch ($passing->status) 
			{
				case TestingPassing::NOT_STARTED :
					$passing->pass_date_start = date('d.m.Y H:i:s');
					break;
				case TestingPassing::MISTAKE :
					Yii::app()->end();
					break;
				case TestingPassing::STARTED :
					$this->redirect(Yii::app()->createUrl('/testings/testingTest/pass', array('id' => $passing->id)));
					Yii::app()->end();
					break;
				case null :
					break;
				case TestingPassing::FAILED :
				case TestingPassing::PASSED :
					$this->render('pass_statistics', array(
						'model' => $passing,
					));
					Yii::app()->end();
					break;
			}

			if($passing->test->mix)
			{
				// Если данные не заполнены не генерируем вопросы к прохождению
				if($passing->test->minutes && $passing->test->questions && $passing->test->pass_percent && $passing->test->attempt)
				{
					$criteria = new CDbCriteria;

					$criteria->with = 'test';
					$criteria->together = true;

					$criteria->addCondition('test.session_id = ' . $passing->test->session_id);
					$criteria->addCondition('t.user_id = ' . $user_id);
					$criteria->addCondition('test.id <> ' . $passing->test->id);

					$criteria->group = 'test.id';

					$passes = TestingPassing::model()->findAll($criteria);

					if($passes)
					{
						// Проверяем хватает ли вопросов в назначенных пользователю тестах
						$questions_count = 0;
						foreach ($passes as $pass) 
						{
							$questions_count += count($pass->test->questionsRelation);
						}

						if($questions_count >= $passing->test->questions)
						{
							// определение количества вопросов, которое нужно взять из каждого теста
							$need_count_questions = ceil($passing->test->questions / count($passes));

							// Берем все вопросы по тестам
							$questions = array();
							$backup = 0;
							foreach ($passes as $pass) 
							{
								$cr = new CDbCriteria;
								$cr->addCondition('test_id = :test_id');
								$cr->addCondition('is_active = :is_active');
								$cr->params = array(
									':test_id' => $pass->test_id,
									':is_active' => TestingQuestion::ACTIVE,
								);
								$test_questions = TestingQuestion::model()->findAll($cr);
								$count_questions = count($test_questions);

								$questions_needed_in_this_test = $need_count_questions + $backup;

								if($questions_needed_in_this_test > $count_questions) 
								{
									$backup = abs($questions_needed_in_this_test - $count_questions);
									$questions_needed_in_this_test = $count_questions;
								} 
								else 
								{
									$backup = 0;
								}

								shuffle($test_questions);

								for ($i = 1; $i <= $questions_needed_in_this_test; $i++) 
								{
									if(count($questions) < $passing->test->questions)
									{
										$questions[] = array_shift($test_questions);
									}
								}
							}

							shuffle($questions);

							if (count($questions) < $passing->test->questions) 
							{
								$diff = abs(count($questions) - $passing->test->questions);
								Yii::app()->user->setFlash('flash',"В назначенных тестах не хватило несколько вопросов ($diff) для заполнения микса! Обратитесь к администратору.");
							}

							if (count($questions) == $passing->test->questions) 
							{
								$passing->is_passed = 0;
								if ($passing->save()) 
								{
									// сначала на всякий случай чистим старые вопросы. вдруг да остались из-за какого глюка..
									$old_questions = TestingQuestionPassing::model()->findAll('passing_id = :passing_id',array(':passing_id' => $passing->id));
									foreach ($old_questions as $old_q) 
									{
										$old_q->delete();
									}

									// а вот теперь заполняем новыми вопросами
									foreach ($questions as $question) 
									{
										$pq = new TestingQuestionPassing;
										$pq->passing_id = $passing->id;
										$pq->question_id = $question->id;
										$pq->question_text = $question->text;
										$pq->answer_text = $question->rightAnswer;
										$pq->save();
									}
								}
							}
						}
						else
						{
							Yii::app()->user->setFlash('flash',"В назначенных тестах не хватает вопросов для заполнения микса! Обратитесь к администратору.");
						}
					}
					else
					{
						Yii::app()->user->setFlash('flash',"Не хватает назначенных тестов для заполнения микса! Обратитесь к администратору.");
					}

					if (($passing->pass_date === null) && ($passing->is_passed === null)) 
					{
						$this->render('info', array(
							'model' => $passing
						));
						Yii::app()->end();
					}
				}
				else
				{
					$this->redirect(Yii::app()->createUrl('/testings/testingTest/index'));
				}				
			}
			else
			{
				// заполнение списка гамм выбранными пользователем, генерация списка вопросов
				if (isset($_POST['mg']) || isset($_POST['te'])) {
					$mg_array = array();
					$te_array = array();
					if (isset($_POST['mg'])) {
						foreach ($_POST['mg'] as $gamma_id) {
							$gamma = TestingGamma::model()->findByPk($gamma_id);
							if ($gamma) {
								$mg_array[] = $gamma;
							}
						}
					}
					if (isset($_POST['te'])) {
						foreach ($_POST['te'] as $gamma_id) {
							$gamma = TestingGamma::model()->findByPk($gamma_id);
							if ($gamma) {
								$te_array[] = $gamma;
							}
						}
					}
					// если выбрано достаточное кол-во гамм продукции, делаем выбор вопросов и начинаем тестирование
					if ((count($mg_array) >= $passing->test->mg_count) && (count($te_array) >= $passing->test->te_count)) {
						// определение количества вопросов, которое нужно взять из каждой основной гаммы
						$mg_questions_count = round($passing->test->questions * $passing->test->mg_percent / ($passing->test->mg_percent + $passing->test->te_percent));
						$te_questions_count = $passing->test->questions - $mg_questions_count;
						// определение количества вопросов, которое нужно взять из каждой гаммы продукции
						$mg_questions_in_each_gamma = count($mg_array) ? ceil($mg_questions_count / count($mg_array)) : 0;
						$te_questions_in_each_gamma = count($te_array) ? ceil($te_questions_count / count($te_array)) : 0;
						// выбор вопросов для строительной гаммы
						$questions = array();
						$backup = 0; // кол-во вопросов, которое не хватило в какой-нибудь другой гамме
						foreach (array_reverse($mg_array) as $gamma) {
							$cr = new CDbCriteria;
							$cr->addCondition('gamma_id = :gamma_id');
							$cr->addCondition('test_id = :test_id');
							$cr->addCondition('is_active = :is_active');
							$cr->params = array(
								':gamma_id' => $gamma->id,
								':test_id' => $passing->test_id,
								':is_active' => TestingQuestion::ACTIVE,
							);
							$questions_needed_in_this_gamma = $mg_questions_in_each_gamma + $backup;
							$questions_in_gamma = TestingQuestion::model()->findAll($cr);
							$count_questions_in_gamma = count($questions_in_gamma);
							// если нужно вопросов больше, чем есть в этой гамме, просим поддержки у остальных гамм
							if ($questions_needed_in_this_gamma > $count_questions_in_gamma) {
								$backup = abs($questions_needed_in_this_gamma - $count_questions_in_gamma);
								$questions_needed_in_this_gamma = $count_questions_in_gamma;
							} else {
								$backup = 0;
							}
							shuffle($questions_in_gamma);
							for ($i=1; $i<=$questions_needed_in_this_gamma; $i++) {
								$questions[] = array_shift($questions_in_gamma);
							}
						}
						foreach (array_reverse($te_array) as $gamma) {
							$cr = new CDbCriteria;
							$cr->addCondition('gamma_id = :gamma_id');
							$cr->addCondition('test_id = :test_id');
							$cr->addCondition('is_active = :is_active');
							$cr->params = array(
								':gamma_id' => $gamma->id,
								':test_id' => $passing->test_id,
								':is_active' => TestingQuestion::ACTIVE,
							);
							$questions_needed_in_this_gamma = $te_questions_in_each_gamma + $backup;
							$questions_in_gamma = TestingQuestion::model()->findAll($cr);
							$count_questions_in_gamma = count($questions_in_gamma);
							// если нужно вопросов больше, чем есть в этой гамме, просим поддержки у остальных гамм
							if ($questions_needed_in_this_gamma > $count_questions_in_gamma) {
								$backup = abs($questions_needed_in_this_gamma - $count_questions_in_gamma);
								$questions_needed_in_this_gamma = $count_questions_in_gamma;
							} else {
								$backup = 0;
							}
							shuffle($questions_in_gamma);
							for ($i=1; $i<=$questions_needed_in_this_gamma; $i++) {
								$questions[] = array_shift($questions_in_gamma);
							}
						}
						// рандомизируем вопросы
						shuffle($questions);
						// если вопросов получилось БОЛЬШЕ, чем надо (погрешности при округлении в большую сторону),
						// тогда убираем несколько лишних
						if (count($questions) > $passing->test->questions) {
							$diff = count($questions) - $passing->test->questions;
							for ($i=1; $i<=$diff; $i++) {
								array_shift($questions);
							}
						}
						// если вопросов получилось МЕНЬШЕ, чем надо,
						// значит вопросов в гаммах не хватило, выводим сообщение пользователю
						if (count($questions) < $passing->test->questions) {
							$diff = abs(count($questions) - $passing->test->questions);
							Yii::app()->user->setFlash('flash',"В выбранных гаммах не хватило несколько вопросов ($diff) для заполнения теста! Обратитесь к администратору.");
						}

						// и вот, если наконец-то всё ок: вопросы выбраны, количество идеально -
						// заполняем базу вопросами и разрешаем юзеру пройти тест
						if (count($questions) == $passing->test->questions) {
							$passing->is_passed = 0;
							if ($passing->save()) {
								// сначала на всякий случай чистим старые вопросы. вдруг да остались из-за какого глюка..
								$old_questions = TestingQuestionPassing::model()->findAll('passing_id = :passing_id',array(':passing_id'=>$passing->id));
								foreach ($old_questions as $old_q) {
									$old_q->delete();
								}
								// а вот теперь заполняем новыми вопросами
								foreach ($questions as $question) {
									$pq = new TestingQuestionPassing;
									$pq->passing_id = $passing->id;
									$pq->question_id = $question->id;
									$pq->question_text = $question->text;
									$pq->answer_text = $question->rightAnswer;
									$pq->save();
								}
							}
						}

					} else {
						Yii::app()->user->setFlash('flash','Выбрано недостаточное количество гамм продукции!');
						foreach ($mg_array as $item) {
							array_unshift($mg_gammas, $item->id);
						}
						foreach ($te_array as $item) {
							array_unshift($te_gammas, $item->id);
						}
					}
				}

				if (($passing->pass_date === null) && ($passing->is_passed === null)) {
					$this->render('pass_fill_gammas', array(
						'model' => $passing,
						'mg_gammas' => $mg_gammas,
						'te_gammas' => $te_gammas,
					));
					Yii::app()->end();
				}
			}

			if (($passing->pass_date === null) && ($passing->is_passed === TestingPassing::FAILED)) 
			{
				$this->redirect(Yii::app()->createUrl('/testings/testingTest/pass', array('id' => $passing->id)));
			} 
			else 
			{
				$this->redirect(Yii::app()->createUrl('/testings/testingTest/statistic', array('id' => $passing->id)));
			}
		}
	}

	public function actionPass($id)
	{
		$this->layout = '/layouts/test';
		$passing = TestingPassing::model()->findByPk($id);

		if(!$passing)
		{
			$this->redirect('/testings/testingTest/index');
		}

		switch ($passing->status) 
		{
			case TestingPassing::STARTED :
				$passing->attempt += 1;
				$passing->save(false, array('attempt'));

				$count_answer = TestingQuestionPassing::model()->countByAttributes(array('passing_id' => $id), 'user_answer IS NOT NULL');

				$this->render('pass_testing', array(
					'model' => $passing,
					'current_answer' => $count_answer + 1
				));
				Yii::app()->end();
				break;
			case TestingPassing::FAILED :
				if($passing->attempt < $passing->test->attempt && strtotime($passing->pass_date_start) + ($passing->test->minutes * 60) >= time())
				{
					$this->redirect(Yii::app()->createUrl('/testings/testingTest/statistic', array('id' => $passing->id)));
					Yii::app()->end();
				}
				else
				{
					if($passing->attempt >= $passing->test->attempt)
					{
						$this->render('pass_not_attempt', array(
							'model' => $passing,
						));
					}
					elseif(strtotime($passing->pass_date_start) + ($passing->test->minutes * 60) < time())
					{
						$this->render('pass_not_time', array(
							'model' => $passing,
						));
					}
					Yii::app()->end();
				}
				break;
			case TestingPassing::PASSED :
				$this->redirect(Yii::app()->createUrl('/testings/testingTest/statistic', array('id' => $passing->id)));
				Yii::app()->end();
				break;
		}
	}

	public function actionStatistic($id)
	{
		$passing = TestingPassing::model()->findByPk($id);

		if(!$passing)
		{
			$this->redirect('/testings/testingTest/index');
		}

		switch ($passing->status) 
		{
			case TestingPassing::FAILED :
			case TestingPassing::PASSED :
				$this->render('pass_statistics', array(
					'model' => $passing,
				));
				Yii::app()->end();
				break;
			default :
				$this->redirect('/testings/testingTest/index');
				break;
		}
	}

	public function loadModel($id)
	{
		$model = TestingTest::model()->findByPk((int)$id);
		if($model === null)
		{
			$this->pageNotFound();
		}

		return $model;
	}


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'testing-test-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
