<?php

namespace common\modules\testings\controllers;

use Yii;
use common\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\modules\testings\models\TestingSession;
use common\modules\testings\models\SearchTestingSession;
use common\modules\testings\models\TestingPassing;
use common\modules\testings\models\TestingTest;

class TestingSessionAdminController extends AdminController
{
    public static function actionsTitles()
    {
        return array(
            'View'   => 'Просмотр сессии',
            'Create' => 'Создание сессии',
            'Update' => 'Редактирование сессии',
            'Extend' => 'Продление сессии',
            'Extend1' => 'Продление сессии для компании',
            'Delete' => 'Удаление сессии',
            'Manage' => 'Управление сессиями',
			'ImportPassings' => 'Импорт пользователей для прохождения тестирования',
			'ExportSessionResult' => 'Экспорт результата сессии',
			'SendMessage' => 'Отправить пользователю сообщение о назначенных тестах',
			'SendMessageToAll' => 'Отправить сообщения о назначенных тестах всем пользователям',
			'SendMessageToMarked' => 'Отправить сообщения отмеченным пользователям',
        );
    }

	public function actionView($id)
	{
		$model = $this->findModel($id);

		$test_ids = array_keys(\yii\helpers\ArrayHelper::map($model->tests, 'id', 'name'));

		$query = TestingPassing::find()
			->joinWith('user')
			->where([
				'test_id' => $test_ids,
			]);

		$query->andWhere(['<>', 'end_date', ""]);

		$users = [];

		foreach ($query->all() as $passing) 
		{
			$users[$passing->end_date] = $passing->user;
		}

		return $this->render('view', [
			'model' => $model,
			'users' => $users
		]);
	}

	public function actionCreate()
	{
		$model = new TestingSession;

        Yii::$app->controller->page_title = 'Добавить сессию';
        Yii::$app->controller->breadcrumbs = [
            ['Список сессий' => '/testings/testing-session-admin/manage'],
            'Добавить сессию'
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['manage']);
        } else {
            $form = new \common\components\BaseForm('/common/modules/testings/forms/TestingSessionForm', $model);
            return $this->render('create', [
                'model' => $model,
                'form' => $form->out
            ]);
        }
	}

	public function actionUpdate($id)
	{
		Yii::$app->controller->page_title = 'Редактировать сессию';
        Yii::$app->controller->breadcrumbs = [
            ['Список сессий' => '/testings/testing-session-admin/manage'],
            'Редактировать сессию'
        ];

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['manage']);
        } else {
            $form = new \common\components\BaseForm('/common/modules/testings/forms/TestingSessionForm', $model);
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
		$searchModel = new SearchTestingSession();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->controller->page_title = 'Список сессий';
        Yii::$app->controller->breadcrumbs = [
            'Список сессий',
        ];

        return $this->render('manage', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}

	public function actionExtend($id)
	{
		$session = $this->loadModel($id);

		$model = new TestingPassing;
		$model->scenario = 'extend';

		$form = new BaseForm('testings.TestingExtendForm', $model);

		if(isset($_POST['TestingPassing']))
		{
			$model->attributes = $_POST['TestingPassing'];

			$userPassing = TestingPassing::model()->find('user_id=:user_id and test_id=:test_id', array(':user_id'=> $model->user_id, ':test_id' => $model->test_id));

			if ($userPassing == null) {
				$model->addError('test_id', 'Нельзя продлить время для теста, который не был назначен пользователю');
			}
			else {
				$userPassing->end_date = $model->end_date;

				if($model->validate())
				{
					if ($userPassing->save()) {
						$this->redirect(array('manage'));
					}
				}
            }
		}

		$this->render('extend', array(
			'form' => $form,
			'session' => $session
		));
	}

    public function actionExtend1($id)
    {
        $session = $this->loadModel($id);

        $model = new TestingPassing;
        //$model->scenario = 'extend';
        $form = new BaseForm('testings.TestingExtend1Form', $model);

        if(isset($_POST['TestingPassing']))
        {
            //$model->attributes = $_POST['TestingPassing'];
            //var_dump($_POST['TestingPassing']);die;
            $users = TestingUser::model()->findAllByAttributes(array('company_name'=>htmlspecialchars_decode($_POST['TestingPassing']['company'])));
            if ($users)
            {
                foreach ($users as $user)
                {
                    $userPassing = TestingPassing::model()->find('user_id=:user_id and test_id=:test_id', array(':user_id'=> $user->id, ':test_id' => $_POST['TestingPassing']['test_id']));
                    if ($userPassing)
                    {
                        $userPassing->end_date = $_POST['TestingPassing']['end_date'];
                        $userPassing->save();
                    }
                }
                $this->redirect(array('manage'));
            }
        }

        $this->render('extend1', array(
            'form' => $form,
            'session' => $session,
        ));
    }

	public function actionExportSessionResult($id)
	{
		$letters = array();
		$count = 1;
		for($col = 'A'; $col != 'ZZ'; $col++) {
			$letters[$count++] = $col;
		}
		/*echo "<pre>";
		print_r($letters);
		echo "</pre>";
		die();*/

		set_time_limit(900);
		ini_set('memory_limit','5250M');
		//ini_set('memory_limit','1024M');

		$session = $this->loadModel($id);

		// get a reference to the path of PHPExcel classes
		$phpExcelPath = Yii::getPathOfAlias('ext.phpexcel.Classes');

		// Turn off our amazing library autoload
		spl_autoload_unregister(array('YiiBase','autoload'));

		// making use of our reference, include the main class
		// when we do this, phpExcel has its own autoload registration
		// procedure (PHPExcel_Autoloader::Register();)
		include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');

		// Create new PHPExcel object
		$excel = new PHPExcel();

		// Once we have finished using the library, give back the
		// power to Yii...
		spl_autoload_register(array('YiiBase','autoload'));

		// Set properties
		$excel->getProperties()->setCreator("Scheinder Electric")
			->setTitle("Excel Title")
			->setSubject("Excel Test Document")
			->setDescription("Test document for excel, generated using PHP classes.")
			->setKeywords("excel php")
			->setCategory("Test result file");

		// стили
		$styleHead = array(
			'font'=>array(
				'name'=>'Arial',
				'size'=>'8',
				'bold'=>true,
			),
			'alignment'=>array(
				'horizontal'=>'center',
				'vertical'=>'center',
			),
			'borders' =>array(
				'outline'=>array(
					'style'=>'thin',
					'color'=> array(
						'argb'=>'FF333333',
					),
				),
				'inside'=>array(
					'style'=>'thin',
					'color'=> array(
						'argb'=>'FF333333',
					),
				),
			),
			'fill'=>array(
				'type'=>'solid',
				'color'=>array(
					'argb'=>'FFC0C0C0',
				),
			),
		);

		$styleHead2 = CMap::mergeArray($styleHead, array(
			'fill'=>array(
				'type'=>'solid',
				'color'=>array(
					'argb'=>'FFCCFFCC',
				),
			),
		));

		$styleHead3 = CMap::mergeArray($styleHead, array(
			'fill'=>array(
				'type'=>'solid',
				'color'=>array(
					'argb'=>'FF969696',
				),
			),
		));

		$styleHead4 = CMap::mergeArray($styleHead, array(
			'font'=>array(
				'name'=>'Arial',
				'size'=>'6',
				'bold'=>true,
			),
		));

		$styleLeft2 = array(
			'font'=>array(
				'name'=>'Arial',
				'size'=>'8',
				'bold'=>false,
			),
			'alignment'=>array(
				'horizontal'=>'left',
				'vertical'=>'bottom',
			),
			'borders' =>array(
				'outline'=>array(
					'style'=>'thin',
					'color'=> array(
						'argb'=>'FF333333',
					),
				),
				'inside'=>array(
					'style'=>'thin',
					'color'=> array(
						'argb'=>'FF333333',
					),
				),
			),
		);

		$styleLeft = CMap::mergeArray($styleLeft2, array(
			'fill'=>array(
				'type'=>'solid',
				'color'=>array(
					'argb'=>'FFC0C0C0',
				),
			),
		));

		$styleNormal = array(
			'font'=>array(
				'name'=>'Tahoma',
				'size'=>'10',
				'bold'=>false,
			),
			'alignment'=>array(
				'horizontal'=>'center',
				'vertical'=>'bottom',
			),
			'borders' =>array(
				'outline'=>array(
					'style'=>'thin',
					'color'=> array(
						'argb'=>'FF333333',
					),
				),
				'inside'=>array(
					'style'=>'thin',
					'color'=> array(
						'argb'=>'FF333333',
					),
				),
			),
		);


		$stylePassed = CMap::mergeArray($styleNormal, array(
			'fill'=>array(
				'type'=>'solid',
				'color'=>array(
					'argb'=>'FF99CC00',
				),
			),
		));

		$styleFailed = CMap::mergeArray($styleNormal, array(
			'fill'=>array(
				'type'=>'solid',
				'color'=>array(
					'argb'=>'FFFF9900',
				),
			),
		));

		// Добавление информации в таблицу
		$excel->setActiveSheetIndex(0);
		$s = $excel->getActiveSheet();

		// шапка таблицы
		$s->mergeCells('A2:A3');
		$s->mergeCells('B2:B3');
		$s->mergeCells('C2:C3');
		$s->mergeCells('D2:D3');
		$s->mergeCells('E2:E3');

		$s->setCellValue('A2', 'Ответственный ТКИ ШЭ');
		$s->setCellValue('B2', 'Город (Регион ШЭ)');
		$s->setCellValue('C2', 'ФИО партнёра');
		$s->setCellValue('D2', 'Название компании');
		$s->setCellValue('E2', 'Город');

		$s->getStyle('A2:B3')->applyFromArray($styleHead3);
		$s->getStyle('C2:E3')->applyFromArray($styleHead2);

		// автоперенос в строках
		$s->getStyle('A2:Z3')->getAlignment()->setWrapText(true);

		$s->getColumnDimension('A')->setWidth('20');
		$s->getColumnDimension('B')->setWidth('18');
		$s->getColumnDimension('C')->setWidth('30');
		$s->getColumnDimension('D')->setWidth('20');
		$s->getColumnDimension('E')->setWidth('18');

		$s->getRowDimension('2')->setRowHeight('40');
		$s->getRowDimension('3')->setRowHeight('40');

		// заголовки для тестов
		//$letter = ord('F');
		$letter = 6;
		$tests = array();
		if ($session->tests) {
//die(print_r($session->tests[10]));
			foreach ($session->tests as $index => $test) {
			//for($index = 0; $index < count($session->tests)-1; $index++) {
				$test = $session->tests[$index];
				$s->mergeCells($letters[$letter].'2:'.$letters[$letter+1].'2');

				$s->setCellValue($letters[$letter].'2', $test->name);
				$s->setCellValue($letters[$letter].'3', '%');
				$s->setCellValue($letters[$letter+1].'3', 'комментарий');

				$s->getColumnDimension($letters[$letter])->setWidth('10');
				$s->getColumnDimension($letters[$letter+1])->setWidth('16');

				$s->getStyle($letters[$letter].'3')->applyFromArray($styleHead4);

				if ($index % 2) {
					$s->getStyle($letters[$letter].'2:'.$letters[$letter+1].'3')->applyFromArray($styleHead2);
				} else {
					$s->getStyle($letters[$letter].'2:'.$letters[$letter+1].'3')->applyFromArray($styleHead);
				}
				$letter +=2;
				$tests[] = $test->id;
				//echo $index."<br>";
				//if($index == 9) die(print_r($_tests));
			}
			
		}
//die('!++!-!-!'.count($session->tests).'---');
		// информация о пользователе, ТКИ и тестах
		$count = 4; // с какой строки начинать

		$passings = Yii::app()->db->createCommand('SELECT * FROM testings_passings WHERE test_id IN ('.implode(',', $tests).') ORDER BY create_date')->queryAll();

		//упорядочим для прямого доступа в дальнейшем
		$pa = array();
		if ($passings) {
			foreach ($passings as $p) {
				$pa[$p['user_id']][$p['test_id']] = $p;
			}
			$passings = $pa;
			unset($pa);
		}
//die(print_r($passings));
		foreach ($session->users as $key => $user) {

			// if($key<1200)
			// 	continue;

			$s->setCellValue('A'.$count, $user->tki);
			$s->setCellValue('B'.$count, $user->region);
			$s->setCellValue('C'.$count, $user->fio);
			$s->setCellValue('D'.$count, $user->company_name);
			$s->setCellValue('E'.$count, $user->city);

			$s->getStyle('A'.$count.':B'.$count)->applyFromArray($styleLeft);
			$s->getStyle('C'.$count.':E'.$count)->applyFromArray($styleLeft2);
			$s->getRowDimension($count)->setRowHeight('12');

			// информация о тестах
			//$letter = ord('F');
			$letter = 6;
			foreach ($tests as $test_id) {
				$s->getStyle($letters[$letter].$count.':'.$letters[$letter+1].$count)->applyFromArray($styleNormal);

				if ($passings) {
					if (isset($passings[$user->id][$test_id])) {
						$passing = $passings[$user->id][$test_id];
					}
				}

				$s->setCellValue($letters[$letter].$count, '-');
				if (isset($passing)) {
					//$s->setCellValue(chr($letter).$count, '1');
					if ($passing['percent_rights']) $s->setCellValue($letters[$letter].$count, $passing['percent_rights']);
					if ((string)$passing['is_passed'] === '1') {
						$s->getStyle($letters[$letter].$count)->applyFromArray($stylePassed);
						$s->setCellValue($letters[$letter+1].$count, 'сдал');
					}
					if ((string)$passing['is_passed'] === '0') {
						$s->getStyle($letters[$letter].$count)->applyFromArray($styleFailed);
						$s->setCellValue($letters[$letter+1].$count, 'не сдал');
					}

					if ($passing['pass_date'] === NULL) {
						$s->setCellValue($letters[$letter+1].$count, 'не сдавал');
					}
				}

				unset($passing);
				$letter +=2;
			}

			$count++;
		}
		// Rename sheet
		$excel->getActiveSheet()->setTitle('Отчёт');

		// Set active sheet index to the first sheet,
		// so Excel opens this as the first sheet
		$excel->setActiveSheetIndex(0);

		$filename = 'Отчёт по сессии '.$session->name.'.xlsx';

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->save('php://output');
		Yii::app()->end();
	}

	public function actionImportPassings($id)
	{
		$model = $this->loadModel($id);
		$group = new TestingUserGroup;

		$params = array('model' => $model, 'group' => $group);

		if (Yii::app()->request->isPostRequest)
		{
			$model->attributes = $_POST['TestingSession'];
			$group->attributes = $_POST['TestingUserGroup'];
			$group->session_id = $model->id;

			if($model->validate() && $group->validate())
			{
				$group->save(false);

				$csv_file = CUploadedFile::getInstance($model, 'csv_file');

				$resource = CSVHelper::open($csv_file->tempName);

				try {
					$log = array();

					set_time_limit(60*5); // Максимальное время выполнения скрипта - 5 минуты

					$usersCount = 0; // кол-во загруженных пользователей
					$passingsCount = 0; // кол-во назначенных тестов
	                
	                $assigned = array();//Для проверки на дубликаты
	                $test_ids = Yii::app()->db->createCommand('SELECT id FROM testings_tests WHERE session_id = :session_id')
	                        ->queryColumn(array('session_id' => $model->id));
	                if(!empty($test_ids)) {
	                    $sql = 'SELECT user_id, test_id FROM testings_passings WHERE test_id IN ('.implode(', ', $test_ids).')';
	                    $rows = Yii::app()->db->createCommand($sql)->queryAll();
	                    foreach($rows as $row) {
	                        $assigned[$row['user_id']][] = $row['test_id'];
	                    }
	                }
	                
					while ($data = CSVHelper::fgetcsv($resource)) 
					{
						// если в файле пошли пустые строки, то выходим из цикла
						if (count($data) < 2) break;

						// извлечение переменных из CSV
						$sex = trim($data[0]);
						$last_name = trim($data[1]);
						$first_name = trim($data[2]);
						$patronymic = trim($data[3]);
						$company_name = trim(preg_replace('/\s+/', ' ',$data[4]));
						$city = trim($data[5]);
						$email = trim($data[6]);
						$login = trim($data[7]);
						$password = trim($data[8]);
						$tki = trim($data[9]);
						$region = trim($data[10]);

						// удаляем уже извлечённые данные; остальные, стало быть, сведения о назначенных тестах
						array_shift($data);
						array_shift($data);
						array_shift($data);
						array_shift($data);
						array_shift($data);
						array_shift($data);
						array_shift($data);
						array_shift($data);
						array_shift($data);
						array_shift($data);
						array_shift($data);

						$usertests = array();

						foreach ($data as $test) 
						{
							$test = trim($test);
							if(in_array($test, array('Комбинированный тест','комбинированный тест','Комбинированный','комбинированный')))
							{
								$mixModel = TestingTest::model()->findByAttributes(array('name'=>'Комбинированный тест','session_id'=>$model->id));

								if(!$mixModel)
								{
									$mixModel = new TestingTest();
									$mixModel->session_id = $model->id;
									$mixModel->name = 'Комбинированный тест';
									$mixModel->mix = true;

									$mixModel->save();
								}
							}
							
							$usertests[] = $test;
						}
						
						if(isset($usertests[0]) && $usertests[0] == 'Вид тестирования') continue;

						// если первый столбец не указывает на пол - вся строка неправильная
						if (($sex <> 'м') && ($sex <> 'ж') && ($sex <> 'М') && ($sex <> 'Ж')) continue;

						$usersCount++;

						// поиск или создание пользователя
						$cr1 = new CDbCriteria;

						$cr1->addCondition('first_name = :first_name');
						$cr1->addCondition('last_name = :last_name');
						$cr1->addCondition('patronymic = :patronymic');
						$cr1->addCondition('company_name = :company_name');

						$cr1->params = array(
							':first_name' => $first_name,
							':last_name' => $last_name,
							':patronymic' => $patronymic,
							':company_name' => $company_name,
						);

						$user = TestingUser::model()->find($cr1);

						if ($user === null)
						{
							$user = new TestingUser;
							$user->last_name = $last_name;
							$user->first_name = $first_name;
							$user->patronymic = $patronymic;
							$user->company_name = $company_name;

							if(($sex == 'Ж') || ($sex == 'ж')) 
							{
								$user->sex = 0;
							} 
							else 
							{
								$user->sex = 1;
							}
							//$log[] = 'Пользователь '.$last_name.' '.$first_name.' '.$patronymic.'["'.$company_name.'"] создан.';
						} 
						else 
						{
							//$log[] = 'Пользователь '.$last_name.' '.$first_name.' '.$patronymic.'["'.$company_name.'"] уже существует.';
						}

						$user->email = $email;
						$user->manager_id = Yii::app()->user->id;
						$user->tki = $tki;
						$user->city = $city;
						$user->region = $region;
						$user->login = $login;
						$user->password = $password;

						if ($user->save()) 
						{
							$command = Yii::app()->db->createCommand();
					        $command->insert('testings_users_groups_assign',  array(
					            'user_id' => $user->id,
					            'group_id' => $group->id,
					            'session_id' => $model->id
					        ));
							//$log[] = 'Пользователь '.$last_name.' '.$first_name.' '.$patronymic.' ["'.$company_name.'"] сохранён.';
						} 
						else 
						{
							$log[] = 'Ошибка при сохранении пользователя '.$last_name.' '.$first_name.' '.$patronymic.' ["'.$company_name.'"]!';
							break;
						}

						// назначение тестов
						$testings = TestingTest::model()->findAll('session_id = :session', array(':session'=>$model->id));

						// проверка на то, создано ли достаточное кол-во тестов в пределах текущей сессии
						if (count($usertests) > count($testings)) 
						{
							throw new Exception('Загружено недостаточное количество тестов в пределах текущей сессии!');
						}
	                    
						foreach ($usertests as $index => $test) 
						{
							if(in_array($test, array('да','Да','ДА','y','yes','1','Y'))) 
							{
								if(!isset($testings[$index]))
								{
									continue;
								}

								if(isset($assigned[$user->id]) && in_array($testings[$index]->id, $assigned[$user->id])) 
								{
	                                $log[] = "Пользователю {$user->id} уже назначен тест № {$testings[$index]->id}";
	                                continue;
	                            }

	                            $pass = new TestingPassing;
								$pass->user_id = $user->id;
								$pass->percent_rights = 0;
								$pass->test_id = $testings[$index]->id;

								if ($pass->save()) 
								{
									//$log[] = 'Пользователю назначен тест "'.$testings[$index]->name.'"';
									$passingsCount++;
	                                $assigned[$user->id][$index] = $testings[$index]->id;
								} 
								else 
								{
									$log[] = 'Ошибка при назначении теста "'.$testings[$index]->name.'"';
								}
							} 
							else 
							{
								//$log[] = 'Тест "'.$testings[$index]->name .'" пользователю не назначается.';
							}
						}
					}

					// добавляем отчёт о кол-ве назначенных тестов
					Yii::app()->user->setFlash('flash', '<i>Всего назначено <b>' .$passingsCount. '</b> тестов <b>'.$usersCount. '</b> пользователям. Перейти к '.CHtml::link('списку назначенных тестов',array('/testings/testingUserAdmin/manage','session'=>$model->id)).'.</i>');


					$params = array(
						'model' => $model,
						'group' => $group,
						'log' => implode('<br />',$log),
					);

				} catch (Exception $e){
					$params['log'] = 'Импорт прошел неудачно: ' . $e->getMessage();
				}
			}
		}
		$this->render('importPassings', $params);
	}

	public function actionSendMessageToAll($id) 
	{
		set_time_limit(0);
		ignore_user_abort(true);

		$session = $this->loadModel($id);
		
		$session->sendMailByList($session->users);

		Yii::app()->user->setFlash('flash', 'Сообщения для всех успешно отправлены!');
		$this->redirect($_SERVER['HTTP_REFERER']);
	}

	public function actionSendMessageToMarked($id) 
	{
		set_time_limit(0);

		$session = $this->loadModel($id);

		$session_key = 'notify_session_'.$id;

		if(isset(Yii::app()->session[$session_key])) 
		{
			$data = unserialize(Yii::app()->session[$session_key]);

			if($data === FALSE)
			{
				$data = array();
			}
		} 
		else
		{
			$data = array();
		}

	    //очищаем список
	    unset(Yii::app()->session[$session_key]);

		if (count($data)) 
		{
			$criteria = new CDbCriteria;
            $criteria->addInCondition( "id" , $data );

            $users = TestingUser::model()->findAll($criteria);

			$session->sendMailByList($users);
		}

		Yii::app()->user->setFlash('flash','Сообщения для выделенных успешно отправлены!');
		$this->redirect($_SERVER['HTTP_REFERER']);
	}

	public function actionSendMessage($id, $user) 
	{
		$user = TestingUser::model()->findByPk($user);

		$session = $this->loadModel($id);

		$session->sendMailByList(array($user));

		Yii::app()->user->setFlash('flash','Сообщение для пользователя "'.$user->fio.'" успешно отправлено!');
		$this->redirect($_SERVER['HTTP_REFERER']);
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
        if (($model = TestingSession::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
