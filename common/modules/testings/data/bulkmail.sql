CREATE TABLE IF NOT EXISTS `bulkmail_accounts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `server` varchar(50) NOT NULL COMMENT '������ �������� ���������',
  `port` int(10) unsigned NOT NULL COMMENT '���� �������� ���������',
  `email` varchar(50) NOT NULL COMMENT 'E-mail �����',
  `username` varchar(50) NOT NULL COMMENT '��� ������������',
  `password` varchar(50) NOT NULL COMMENT '������ ��� �����������',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '���� ��������',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bulkmail_queue` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `emailTo` varchar(100) NOT NULL COMMENT 'Email ����������',
  `subject` varchar(250) NOT NULL COMMENT '���� ���������',
  `body` text NOT NULL COMMENT '����� ���������',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '���� ��������',
  `send_date` timestamp NULL DEFAULT NULL COMMENT '���� ��������',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bulkmail_state` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date_last` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '��������� ������',
  `qtyTotal` int(11) NOT NULL COMMENT '����� �� ��������',
  `qtyMod` float NOT NULL COMMENT '������� �������',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;