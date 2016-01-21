CREATE TABLE IF NOT EXISTS `bulkmail_accounts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `server` varchar(50) NOT NULL COMMENT 'Сервер отправки сообщений',
  `port` int(10) unsigned NOT NULL COMMENT 'Порт отправки сообщений',
  `email` varchar(50) NOT NULL COMMENT 'E-mail адрес',
  `username` varchar(50) NOT NULL COMMENT 'Имя пользователя',
  `password` varchar(50) NOT NULL COMMENT 'Пароль для авторизации',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bulkmail_queue` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `emailTo` varchar(100) NOT NULL COMMENT 'Email получателя',
  `subject` varchar(250) NOT NULL COMMENT 'Тема сообщения',
  `body` text NOT NULL COMMENT 'Текст сообщения',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания',
  `send_date` timestamp NULL DEFAULT NULL COMMENT 'Дата отправки',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bulkmail_state` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date_last` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Последний запуск',
  `qtyTotal` int(11) NOT NULL COMMENT 'Писем на отправку',
  `qtyMod` float NOT NULL COMMENT 'Перенос остатка',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;