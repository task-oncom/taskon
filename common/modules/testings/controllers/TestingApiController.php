<?php

class TestingApiController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            'Test' => 'API',
            'Test2' => 'API',
            'Test3' => 'API',
            'Send' => 'API',
			'Index' => 'Работа с API unisender',
			'Prepair' => 'Выгрузка в unisender',
			'List' => 'Cписки рассылки с их кодами',
			'DeleteList' => 'Удаление списка рассылки',
			'GetUnique' => 'Уникальные польвоатели',
        );
    }
	public $right = array(
		);
	
	public function actionTest2() {
		$phpExcelPath = Yii::getPathOfAlias('ext.phpexcel.Classes');
		spl_autoload_unregister(array('YiiBase','autoload'));
		include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
		spl_autoload_register(array('YiiBase', 'autoload'));
		
		Yii::import('application.modules.testings.models.*');
		for($t = 36; $t<=43; $t++) {
		$test = TestingTest::model()->findByPk($t);
		//$question = TestingQuestion::model()->findAll('test_id =:test_id', array(':test_id'=>36));
		$passing = TestingPassing::model()->findAll('test_id = :test_id', array(':test_id'=>$t));
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->setTitle("Тесты");
		
		$sheet = $objPHPExcel->getActiveSheet();
		
		//$sheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
		$sheet->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		
		$sheet->setCellValue('A1', 'Пользователь');
		$sheet->setCellValue('B1', 'Статус');
		
		
		$exelArray = $this->generateExelArray();
		$i = 1;
		$sheet->getRowDimension(1)->setRowHeight(-1); 
		
		/*foreach($question as $q) {
			$answer = TestingAnswer::model()->find('question_id=:question_id AND is_right =1', array(':question_id'=>$q->id));
			$sheet->setCellValue($exelArray[$i].'1', $q->text.' (Правильный ответ: '.$answer->text.')');
			
			$sheet->getColumnDimension($exelArray[$i])->setWidth(20);
			$questionArray[$q->text] = $exelArray[$i];
			$i++;
		}*/
		
		//$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getStyle('A1:AZ1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)->setWrapText(true);
		$sheet->getColumnDimension('A')->setAutoSize(true);
		
		$i = 2;
		
		foreach($passing as $data) {
			$k = 1;
			
			$user = TestingUser::model()->findByPk($data->user_id);
			
			if($user->company_name != 'Компания ЭТМ')
				continue;
			
			$sheet->setCellValue('A'.$i, $user->last_name.' '.$user->first_name.' '.$user->patronymic);
			if($data->is_passed == '')
				$sheet->setCellValue('B'.$i, 'Не сдавал');
			
			elseif($data->is_passed == 0) {
				$sheet->setCellValue('B'.$i, 'Не сдал');
				$sheet->getStyle('B'.$i)->getFill()->applyFromArray(
					array(
						'type'       => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array('rgb' => 'f9634b'),
					)
				);
			}
			
			elseif($data->is_passed == 1) {
				$sheet->setCellValue('B'.$i, 'Сдал');
				$sheet->getStyle('B'.$i)->getFill()->applyFromArray(
					array(
						'type'       => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array('rgb' => '4bf9ad'),
					)
				);
			}
			//echo $user->last_name.' '.$user->first_name.' '.$user->patronymic.' -> |'.$data->is_passed.'|<hr/>';
			/*foreach($data->questions as $c) {
			
				$sheet->setCellValue($questionArray[$c->question->text].$i, $c->user_answer);
				$rightAnswer = $c->user_answer;
					
				$answer = TestingAnswer::model()->find('question_id=:question_id AND is_right =1', array(':question_id'=>$c->question->id));
				
				$rightAnswer = $answer->text;
				
				if($c->question->rightAnswer == $c->user_answer){
					$sheet->getStyle($questionArray[$c->question->text].$i)->getFill()->applyFromArray(
						array(
							'type'       => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array('rgb' => '4bf9ad'),
						)
					);
				}
				else {
					$sheet->getStyle($questionArray[$c->question->text].$i)->getFill()->applyFromArray(
						array(
							'type'       => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array('rgb' => 'f9634b'),
						)
					);
				}
				
				if(in_array($c->user_answer, $this->right)) {
					$sheet->getStyle($questionArray[$c->question->text].$i)->getFill()->applyFromArray(
						array(
							'type'       => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array('rgb' => '4bf9ad'),
						)
					);
				}
				
			}
			*/
			$sheet->getRowDimension($i)->setRowHeight(-1);
			$i++;
		}
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");

		$fileName = md5(date("YmdHis").rand(1,1000));

		$objWriter->save(Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$fileName.'.xlsx');

		echo '<a href = "http://partnersnet.schneider-electric.ru/upload/tmp/'.$fileName.'.xlsx">'.$test->name.'</a><br/>';
		
		}
		
	}	
	
	
	public function actionTest3() {
		$phpExcelPath = Yii::getPathOfAlias('ext.phpexcel.Classes');
		spl_autoload_unregister(array('YiiBase','autoload'));
		include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
		spl_autoload_register(array('YiiBase', 'autoload'));
		
		Yii::import('application.modules.testings.models.*');
		for($t = 36; $t<=43; $t++) {
		$test = TestingTest::model()->findByPk($t);
		//$question = TestingQuestion::model()->findAll('test_id =:test_id', array(':test_id'=>36));
		$passing = TestingPassing::model()->findAll('test_id = :test_id', array(':test_id'=>$t));
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->setTitle("Тесты");
		
		$sheet = $objPHPExcel->getActiveSheet();
		
		//$sheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
		$sheet->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		
		$sheet->setCellValue('A1', 'Пользователь');
		$sheet->setCellValue('B1', 'Статус');
		
		
		$exelArray = $this->generateExelArray();
		$i = 1;
		$sheet->getRowDimension(1)->setRowHeight(-1); 
		
		/*foreach($question as $q) {
			$answer = TestingAnswer::model()->find('question_id=:question_id AND is_right =1', array(':question_id'=>$q->id));
			$sheet->setCellValue($exelArray[$i].'1', $q->text.' (Правильный ответ: '.$answer->text.')');
			
			$sheet->getColumnDimension($exelArray[$i])->setWidth(20);
			$questionArray[$q->text] = $exelArray[$i];
			$i++;
		}*/
		
		//$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getStyle('A1:AZ1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)->setWrapText(true);
		$sheet->getColumnDimension('A')->setAutoSize(true);
		
		$i = 2;
		
		$city = array('Новосибирск', 'Красноярск', 'Омск', 'Барнаул', 'Кемерово', 'Томск', 'Новокузнецк', 'Бийск', 'Бердск','Иркутск', 'Братск','Ангарск');
		$tki = array('Рихтер Вадим Сергеевич', 'адорожний Максим Александрович', 'Бородин Иван Викторович');
		
		foreach($passing as $data) {
			$k = 1;
			
			$user = TestingUser::model()->findByPk($data->user_id);

			if(!in_array($user->tki, $tki))
				continue;
			
			if(!in_array($user->city, $city))
				continue;
			
			$sheet->setCellValue('A'.$i, $user->last_name.' '.$user->first_name.' '.$user->patronymic);
			if($data->is_passed == '')
				$sheet->setCellValue('B'.$i, 'Не сдавал');
			
			elseif($data->is_passed == 0) {
				$sheet->setCellValue('B'.$i, 'Не сдал');
				$sheet->getStyle('B'.$i)->getFill()->applyFromArray(
					array(
						'type'       => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array('rgb' => 'f9634b'),
					)
				);
			}
			
			elseif($data->is_passed == 1) {
				$sheet->setCellValue('B'.$i, 'Сдал');
				$sheet->getStyle('B'.$i)->getFill()->applyFromArray(
					array(
						'type'       => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array('rgb' => '4bf9ad'),
					)
				);
			}
			//echo $user->last_name.' '.$user->first_name.' '.$user->patronymic.' -> |'.$data->is_passed.'|<hr/>';
			/*foreach($data->questions as $c) {
			
				$sheet->setCellValue($questionArray[$c->question->text].$i, $c->user_answer);
				$rightAnswer = $c->user_answer;
					
				$answer = TestingAnswer::model()->find('question_id=:question_id AND is_right =1', array(':question_id'=>$c->question->id));
				
				$rightAnswer = $answer->text;
				
				if($c->question->rightAnswer == $c->user_answer){
					$sheet->getStyle($questionArray[$c->question->text].$i)->getFill()->applyFromArray(
						array(
							'type'       => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array('rgb' => '4bf9ad'),
						)
					);
				}
				else {
					$sheet->getStyle($questionArray[$c->question->text].$i)->getFill()->applyFromArray(
						array(
							'type'       => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array('rgb' => 'f9634b'),
						)
					);
				}
				
				if(in_array($c->user_answer, $this->right)) {
					$sheet->getStyle($questionArray[$c->question->text].$i)->getFill()->applyFromArray(
						array(
							'type'       => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array('rgb' => '4bf9ad'),
						)
					);
				}
				
			}
			*/
			$sheet->getRowDimension($i)->setRowHeight(-1);
			$i++;
		}
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");

		$fileName = md5(date("YmdHis").rand(1,1000));

		$objWriter->save(Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'upload'.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$fileName.'.xlsx');

		echo '<a href = "http://partnersnet.schneider-electric.ru/upload/tmp/'.$fileName.'.xlsx">'.$test->name.'</a><br/>';
		
		}
		
	}
	
	public function generateExelArray($number=300) {
		$min = ord("A"); // ord returns the ASCII value of the first character of string.
		$firstChar = ""; // Initialize the First Character
		$abc = $min; // Initialize our alphabetical counter
		
		for($j = 0; $j <= $number; ++$j) {
			$col = $firstChar.chr($abc); // This is the Column Label.
			$last_char = substr($col, -1);
		
			if ($last_char> "Z") {// At the end of the alphabet. Time to Increment the first column letter.
				$abc = $min; // Start Over
			
				if ($firstChar == "") // Deal with the first time.
					$firstChar = "A";
				else {
					$fchrOrd = ord($firstChar);// Get the value of the first character
					$fchrOrd++; // Move to the next one.
					$firstChar = chr($fchrOrd); // Reset the first character.
				}
				 $col = $firstChar.chr($abc); // This is the column identifier
			}
			$exel[$j]=$col;
			/*
			Use the $col here.
			*/
		 
			$abc++; // Move on to the next letter
		}
		return $exel;
	}
	
	
	public function actionTest()
	{
		$a = TestingTest::model()->findAll('session_id=:session_id', array(':session_id'=>12));
		$d = array();
		foreach($a as $c) {
			$d[] = $c->id;
		}
		
		$criteria = new CDbCriteria();
		$criteria->addInCondition("test_id", $d);

		$c = TestingPassing::model()->findAll($criteria);
		foreach($c as $p) {
			$user[$p->user_id] = $p->user_id;
		}
		
		echo count($user);
	}
	
	public function actionIndex() {
	
		$this->render('index');
	}
	
	
	public function actionSend()
	{
		//error_reporting(E_ALL);
		set_time_limit(60*60*50);
		$api = new UniSenderApi;
		$sqlData = $this->getUnique(Yii::app()->request->getParam('sessionId'));
		
		sort($sqlData);
		
		$fields = array (
		  //'api_key'=>$api->getApiKey(),
		  'field_names[0]' => 'email',
		  'field_names[1]' => 'email_list_ids',
		  'field_names[2]' => 'email_status',
		  'field_names[3]' => 'Name',
		  'field_names[4]' => 'LastName',
		  'field_names[5]' => 'Patronymic',
		  'field_names[6]' => 'Company',
		  'field_names[7]' => 'City',
		  'field_names[8]' => 'TKI',
		  'field_names[9]' => 'Region',
		  'field_names[10]' => 'Login',
		  'field_names[11]' => 'Password',
		  'field_names[12]' => 'Session',
		  
		);

		$start = Yii::app()->request->getParam('start');
		$end = $start + Yii::app()->request->getParam('limit');
		
		for($i = $start; $i < count($sqlData); $i++) {
						
			$c = TestingUser::model()->findByPk($sqlData[$i]);
			
			$data['data[' . $i .'][0]'] = $c->email;
			$data['data[' . $i .'][1]'] = Yii::app()->request->getParam('groupId');
			$data['data[' . $i .'][2]'] = 'active';
			$data['data[' . $i .'][3]'] = $c->first_name;
			$data['data[' . $i .'][4]'] = $c->last_name;
			$data['data[' . $i .'][5]'] = $c->patronymic;
			$data['data[' . $i .'][6]'] = $c->company_name;
			$data['data[' . $i .'][7]'] = $c->city;
			$data['data[' . $i .'][8]'] = $c->tki;
			$data['data[' . $i .'][9]'] = $c->region;
			$data['data[' . $i .'][10]'] = $c->login;
			$data['data[' . $i .'][11]'] = $c->password;
			$data['data[' . $i .'][12]'] = $c->password;
			
			if($i > $end)
				break;
			
			$emails[] = $c->email;
		}
		$tt = array_values(array_unique($emails));
	
		
		$result = $api -> __call('importContacts', array_merge($fields,$data));
		$data = $api->checkErrors($result);
		if($data['status'] == true) {
			echo 'Новые подписчики успешно добавлены!';
		} else
			echo $data['data'];
	}
	
	protected function search($session) {
	
		$criteria = new CDbCriteria;
		
		$criteria->with = array('passings');
		$criteria->together = true;
		$criteria->group = 't.id';
		$criteria->compare('passings.session_id', $session, true);

		//$criteria->order = 'last_name, first_name, patronymic';
		$criteria->order = 't.id DESC';
		return $criteria;
	
	}
	
	public function actionList() {
		if(isset($_POST['unisender_new_list'])) {
			$api = new UniSenderApi;
			$result = $api->__call('createList', array('title'=>$_POST['unisender_new_list']));
			$obj = $api->checkErrors($result);
			if($obj['status'] == true) {
				Yii::app()->user->setFlash('list_generate', 'Новый список успешно создан! #'.$obj['data']->result->id);
			}
			else {
				Yii::app()->user->setFlash('list_generate', 'Ошибка при создании нового списка! '.$obj['data']);
			}
			
			
		}
		$this->render('list');
	}
	
	public function actionDeleteList() {
		$listId = Yii::app()->request->getParam('id');
		$api = new UniSenderApi;
		$result = $api->__call('deleteList', array('list_id'=>$listId));
		$obj = $api->checkErrors($result);
		if($obj['status'] == true) {
			echo 'Список успешно удален!';
		}
		else {
			echo 'Ошибка при удалении списка! '.$obj['data'];
		}
	}
	public function actionPrepair() {
		$this->render('prepair');
	}
	
	public function actionGetUnique() {
		echo count($this->getUnique(Yii::app()->request->getParam('sessionId')));
	}
	
	public function getUnique($sessionId) {
		$a = TestingTest::model()->findAll('session_id=:session_id', array(':session_id'=>$sessionId));
		$d = array();
		
		foreach($a as $c) {
			$d[] = $c->id;
		}
		
		$criteria = new CDbCriteria();
		$criteria->addInCondition("test_id", $d);

		$c = TestingPassing::model()->findAll($criteria);
		$k = 0;
		
		$datas = array();
		
		foreach($c as $p) {
			$datas[$k] = $p->user_id;
			$k++;
		}
		
		return $sqlData = array_values(array_unique($datas));
	}
	
}
