<?php
class TestingPassingController extends BaseController
{

	public $layout = '/layouts/testing';
	public $page_subtitle;

    public static function actionsTitles()
    {
        return array(
            'Index'   => 'Просмотр прохождения',
            'Session' => 'Прохождение сессии',
        );
    }

    public function filters() {
		return CMap::mergeArray(parent::filters(), array(
			'checkAuth - login',
		));
	}

	public function filterCheckAuth($filterChain) {
		$data = explode('.', $_SERVER['SERVER_NAME']);
		if(!in_array('www',$data))
			$this->redirect('http://www.partnersnet.schneider-electric.ru/testings/testingPassing/');
		
		if (!in_array(Yii::app()->user->role, array('schneider_electric', 'admin'))) {
			$this->redirect('/login/');
		}
		$filterChain->run();
	}

	public function actionIndex() {

		$sessions = TestingSession::model()->findAll();

		$user = User::model()->findByPk(Yii::app()->user->id);
		$cr = new CDbCriteria;
		$cr->with = array('user', 'test');
		$name = trim(implode(' ', array($user->last_name, $user->first_name, $user->patronymic)));
		//echo $name = 'Алешин Алексей Евгеньевич';
		//$cr->addCondition('user.tki = "'.$user->last_name.' '.$user->first_name.' '.$user->patronymic.'"');
		$cr->compare('user.tki', $name, true);
		//$cr->compare('user.manager_id', Yii::app()->user->id);
		//$cr->limit = 300;
		$cr->together = true;
		$passings = TestingPassing::model()->findAll($cr);
		
		//соотнесем тесты с сессиями
		$tests_list = array();
		$tests = TestingTest::model()->findAll();
		foreach ($tests as $test) {
			$tests_list[$test->id] = $test->session_id;
		}
		$tests = $tests_list;

		//отсортируем сдачи тестов по сессиям
		$passings_list = array();
		foreach ($passings as $p) {
			$session_id = $tests[$p->test_id];
			$passings_list[$session_id][] = $p;
		}
		$passings = $passings_list;

		$this->render('index', array('sessions' => $sessions, 'passings' => $passings));
	}
}
