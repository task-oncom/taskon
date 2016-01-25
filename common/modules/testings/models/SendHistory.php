<?php

namespace common\modules\testings\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Url;

class SendHistory extends \common\components\ActiveRecordModel
{
    const PAGE_SIZE = 10;

    const FOLDER_PATH = '/uploads/dublicates/';

    const OFFSET_EXCEL_ROWS = 3;

    const STATUS_NOT_SENT = 'not_sent';
    const STATUS_OK_SENT = 'ok_sent';
    const STATUS_OK_DELIVERED = 'ok_delivered';
    const STATUS_OK_READ = 'ok_read';
    const STATUS_OK_FBL = 'ok_fbl';
    const STATUS_OK_LINK_VISITED = 'ok_link_visited';
    const STATUS_OK_UNSUBSCRIBED = 'ok_unsubscribed';
    const STATUS_ERR_USER_UNKNOWN = 'err_user_unknown';
    const STATUS_ERR_USER_INACTIVE = 'err_user_inactive';
    const STATUS_ERR_MAILBOX_FULL = 'err_mailbox_full';
    const STATUS_ERR_SPAM_REJECTED = 'err_spam_rejected';
    const STATUS_ERR_SPAM_FOLDER = 'err_spam_folder';
    const STATUS_ERR_DELIVERY_FAILED = 'err_delivery_failed';
    const STATUS_ERR_WILL_RETRY = 'err_will_retry';
    const STATUS_ERR_RESEND = 'err_resend';
    const STATUS_ERR_DOMAIN_INACTIVE = 'err_domain_inactive';
    const STATUS_ERR_SKIP_LETTER = 'err_skip_letter';
    const STATUS_ERR_SPAM_SKIPPED = 'err_spam_skipped';
    const STATUS_ERR_SPAM_RETRY = 'err_spam_retry';
    const STATUS_ERR_UNSUBSCRIBED = 'err_unsubscribed';
    const STATUS_ERR_SRC_INVALID = 'err_src_invalid';
    const STATUS_ERR_DEST_INVALID = 'err_dest_invalid';
    const STATUS_ERR_NOT_ALLOWED = 'err_not_allowed';
    const STATUS_ERR_NOT_AVAILABLE = 'err_not_available';
    const STATUS_ERR_LOST = 'err_lost';
    const STATUS_ERR_INTERNAL = 'err_internal';

    private static $xl_template = [
    	'last_name' => 'Фамилия',
    	'first_name' => 'Имя',
    	'patronymic' => 'Отчество',
    	'login' => 'Логин',
    	'password' => 'Пароль',
    ];

	public static function tableName()
	{
		return 'testings_users_history';
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

    public function name()
    {
        return 'История отправки доступов';
    }

    public function rules()
	{
		return [
			[['email', 'session_id'], 'required'],
			['file', 'sended', 'user_id', 'unisender_email', 'unisender_status', 'notified', 'safe'],
			// array('id, email, session_id, file, sended, user_id', 'safe', 'on' => 'search'),
		];
	}

	public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getSession()
    {
        return $this->hasOne(Session::className(), ['id' => 'session_id']);
    }

	public function attributeLabels() 
	{
		return [
			'email' => 'Email',
			'session_id' => 'Сессия',
			'file' => 'Файл',
			'sended' => 'Дата отправки',
			'user_id' => 'Пользователь',
			'unisender_email' => 'UniSender ID',
			'unisender_status' => 'Статус отправки',
		];
	}

	public static function getStatusTitle($status = null)
	{
		$list = [
			self::STATUS_NOT_SENT => 'Сообщение еще не было обработано',
		    self::STATUS_OK_SENT => 'Сообщение было отправлено',
		    self::STATUS_OK_DELIVERED => 'Сообщение доставлено',
		    self::STATUS_OK_READ => 'Сообщение доставлено и зарегистрировано его прочтение',
		    self::STATUS_OK_FBL => 'Сообщение доставлено, но помещено в папку "спам" получателем',
		    self::STATUS_OK_LINK_VISITED => 'Сообщение доставлено, прочитано и выполнен переход по одной из ссылок',
		    self::STATUS_OK_UNSUBSCRIBED => 'Сообщение доставлено и прочитано, но пользователь отписался по ссылке в письме',
		    self::STATUS_ERR_USER_UNKNOWN => 'Адрес не существует, доставка не удалась',
		    self::STATUS_ERR_USER_INACTIVE => 'Адрес когда-то существовал, но сейчас отключен',
		    self::STATUS_ERR_MAILBOX_FULL => 'Почтовый ящик получателя переполнен',
		    self::STATUS_ERR_SPAM_REJECTED => 'Письмо отклонено сервером как спам',
		    self::STATUS_ERR_SPAM_FOLDER => 'Письмо помещено в папку со спамом почтовой службой',
		    self::STATUS_ERR_DELIVERY_FAILED => 'Доставка не удалась по иным причинам',
		    self::STATUS_ERR_WILL_RETRY => 'Одна или несколько попыток доставки оказались неудачными, но попытки продолжаются',
		    self::STATUS_ERR_RESEND => 'Одна или несколько попыток доставки оказались неудачными, но попытки продолжаются',
		    self::STATUS_ERR_DOMAIN_INACTIVE => 'Домен не принимает почту или не существует',
		    self::STATUS_ERR_SKIP_LETTER => 'Адресат не является активным - он отключён или заблокирован',
		    self::STATUS_ERR_SPAM_SKIPPED => 'Сообщение не отправлено, т.к. большая часть рассылки попала в cпам и остальные письма отправлять не имеет смысла',
		    self::STATUS_ERR_SPAM_RETRY => 'Письмо ранее не было отправлено из-за подозрения на спам, но после расследования выяснилось, что всё в порядке и его нужно переотправить',
		    self::STATUS_ERR_UNSUBSCRIBED => 'Отправка не выполнялась, т.к. адрес, по которому пытались отправить письмо, ранее отписался',
		    self::STATUS_ERR_SRC_INVALID => 'Неправильный адрес отправителя',
		    self::STATUS_ERR_DEST_INVALID => 'Неправильный адрес получателя',
		    self::STATUS_ERR_NOT_ALLOWED => 'Возможность отправки писем заблокирована системой из-за нехватки средств на счету или сотрудниками технической поддержки вручную',
		    self::STATUS_ERR_NOT_AVAILABLE => 'Адрес, по которому пытались отправить письмо, не является доступным',
		    self::STATUS_ERR_LOST => 'Письмо было утеряно из-за сбоя на нашей стороне, и отправитель должен переотправить письмо самостоятельно',
		    self::STATUS_ERR_INTERNAL => 'Внутренний сбой',
		];
		
		if($status)
		{
			if(in_array($status, array_keys($list)))
			{
				return $list[$status];
			}

			return $status;
		}

		return $list;
	}

	public function getFilePath()
	{
		return Yii::getAlias('@webroot') . self::FOLDER_PATH . $this->file;
	}

	public function getFileUrl()
	{
		return Url::to([self::FOLDER_PATH . $this->file]);
	}

	public function generateFile($users, $session_id)
	{
		$path = Yii::getPathOfAlias('webroot') . SendHistory::FOLDER_PATH;
		mkdir($path, 0777, true);

		$filename = 'spisok_dostupov_k_testirovaniyu_' . date('Y-m-d_His') . '_' . uniqid() . '.xlsx';
		$file = $path . $filename;

		$phpExcelPath = Yii::getPathOfAlias('ext.phpexcel.Classes');      
		require_once($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);

		$sheet = $objPHPExcel->getActiveSheet();

		$session = Session::model()->findByPK($session_id);

		$info = "Список доступов сотрудников к сессии ".$session->name." 
				Указанная почта: ".$users[0]->email." 
				Дата: " . date('d.m.Y');

		$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri')->setSize(12);

		$sheet->getColumnDimension("A")->setWidth(25);
		$sheet->getColumnDimension("B")->setWidth(25);
		$sheet->getColumnDimension("C")->setWidth(25);
		$sheet->getColumnDimension("D")->setWidth(25);
		$sheet->getColumnDimension("E")->setWidth(25);

		$sheet->setCellValue('A1', $info);
		$sheet->mergeCells('A1:E1');
		$sheet->getRowDimension('1')->setRowHeight(62);
		$sheet->getStyle('A1:E1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle("A1:E1")->getAlignment()->setWrapText(true);

		$sheet->getStyle('A2:E2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A2:E2')->getFill()->getStartColor()->setARGB('92D050');
        $sheet->getStyle('A2:E2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyle('A2:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$sheet->getStyle("A2:E2")->getFont()->setBold(true);

		$i = 0;
		foreach (self::$xl_template as $name => $title) 
		{
			$sheet->setCellValueByColumnAndRow($i, 2, $title);
			$i++;
		}

		foreach ($users as $i => $user) 
		{
			$row = $i + self::OFFSET_EXCEL_ROWS;
			$j = 0;

			$sheet->getStyle('A'.$row.':E'.$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

			if($row%2==0)
			{
				$sheet->getStyle('A'.$row.':E'.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	        	$sheet->getStyle('A'.$row.':E'.$row)->getFill()->getStartColor()->setARGB('F2F2F2');
	        }

			foreach (self::$xl_template as $name => $title) 
			{
				$sheet->setCellValueByColumnAndRow($j, $row, $user->$name);
				$j++;
			}
		}

		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save($file);

		return $filename;
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('id', $this->id);
		$criteria->compare('email', $this->email, true);

		$criteria->compare('session_id', Yii::app()->request->getQuery('session'), true);

		$criteria->compare('file', $this->file, true);
		$criteria->compare('sended', $this->sended, true);
		$criteria->compare('user_id', $this->user_id, true);

        $criteria->addCondition("user_id = 0");

		return new ActiveDataProvider(get_class($this), array(
			'criteria' => $criteria
		));
	}
}