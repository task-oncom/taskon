<?php

class TestingFaqController extends BaseController
{
	const AUTH_COOKIE = 'test_user_cookie';
	const PASS_COOKIE = 'test_pass_cookie';

	public $layout = '/layouts/testing';

	public $page_subtitle;

	public $logined_user;

	public function filters() {
		return CMap::mergeArray(parent::filters(), array(
			'checkAuth - login',
		));
	}

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

	private function encodePassword($pass) {
		return md5($pass.'the.longest.salt.ever.dont.even.try.to.decode.lol');
	}

	public static function actionsTitles()
	{
		return array(
			'View' => 'Справка',
		);
	}

	public function actionView($url)
	{
		$model = $this->loadModel($url);

		$this->render('view', array(
			'model' => $model,
		));
	}

	public function loadModel($url)
	{
		$model = TestingFaq::model()->findByAttributes(array('url' => $url));
		if($model === null)
		{
			$this->pageNotFound();
		}

		return $model;
	}
}
